<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\Hwinfo;
use Throwable;
use JsonException;

class UploadController extends Controller
{
    const ROLE_FREE = 'free';
    const ROLE_PLUS = 'plus';
    const ROLE_PRO  = 'pro';

    public function index()
    {
        try {
            $user = auth()->user();
            $role = strtolower((string) ($user->role ?? self::ROLE_FREE));

            $limits = $this->resolveLimitsForRole($role);
            $projectCount   = $user->projects()->count();
            $remainingQuota = $limits['maxProjects'] - $projectCount;

            return view('uploads', [
                'projectCount'    => $projectCount,
                'maxLimit'        => $limits['maxProjects'],
                'remainingQuota'  => $remainingQuota,
                'hasReachedLimit' => $remainingQuota <= 0,
                'maxUploadMb'     => $limits['maxFileMb'],
            ]);
        } catch (Throwable $e) {
            Log::error('Upload index failed', [
                'user_id' => optional(auth()->user())->id,
                'error'   => $e->getMessage()
            ]);
            toastr()->error('Failed to load upload page.');
            return back();
        }
    }

    public function store(Request $request)
    {
        try {
            $user = auth()->user();
            $role = strtolower((string) ($user->role ?? self::ROLE_FREE));
            $limits = $this->resolveLimitsForRole($role);

            $projectCount = $user->projects()->count();
            if ($projectCount >= $limits['maxProjects']) {
                toastr()->warning("Upload limit {$limits['maxProjects']} reached for your plan.");
                return back()->withErrors([
                    'video' => "You have reached your maximum video analysis limit ({$limits['maxProjects']} projects) for your {$role} plan. Please upgrade to increase the limit.",
                ])->withInput();
            }

            $allowedMimes = implode(',', config('files.upload.allowed_mimes', ['mp4']));
            $maxUploadKb  = $limits['maxFileMb'] * 1024;

            $request->validate([
                'video'        => "required|mimes:{$allowedMimes}|max:{$maxUploadKb}",
                'project_name' => 'required|string|max:255',
                'description'  => 'nullable|string',
            ], [
                'video.max'   => "The video may not be greater than {$limits['maxFileMb']} MB for your {$role} plan.",
                'video.mimes' => "The video must be a file of type: {$allowedMimes}.",
            ]);

            // Create project detail first
            $projectDetail = ProjectDetail::create([
                'description' => $request->input('description'),
            ]);

            if (!$projectDetail || !$projectDetail->id) {
                Log::error('ProjectDetail create failed');
                toastr()->error('Failed to create project detail.');
                return back()->withInput();
            }

            $project = Project::create([
                'user_id'            => $user->id,
                'project_details_id' => $projectDetail->id,
                'project_name'       => $request->input('project_name'),
                'upload_date'        => now(),
            ]);

            if (!$project || !$project->id) {
                Log::error('Project create failed', ['user_id' => $user->id]);
                $projectDetail->delete();
                toastr()->error('Failed to create project.');
                return back()->withInput();
            }

            // Upload video ke Google Cloud Storage
            $file          = $request->file('video');
            $originalName  = $file->getClientOriginalName();
            $localFilePath = $file->getPathname();

            $bucket     = env('GCS_BUCKET', 'courtplay-storage');
            $objectName = "uploads/videos/{$user->id}/{$project->id}/{$originalName}";
            $keyPath    = env('GCS_KEY_PATH', 'storage/app/keys/courtplay-gcs-key.json');
            $keyFile    = base_path($keyPath);
            $gac        = env('GOOGLE_APPLICATION_CREDENTIALS', $keyPath);

            try {
                if (!getenv('GOOGLE_APPLICATION_CREDENTIALS')) {
                    putenv("GOOGLE_APPLICATION_CREDENTIALS={$gac}");
                }

                $publicUrl = upload_object($bucket, $objectName, $localFilePath, $keyFile);

                if (is_string($localFilePath) && file_exists($localFilePath)) {
                    @unlink($localFilePath);
                }

                $projectDetail->update(['link_video_original' => $publicUrl]);
            } catch (Throwable $e) {
                Log::error('GCS upload failed', [
                    'user_id'    => $user->id,
                    'project_id' => $project->id,
                    'bucket'     => $bucket,
                    'key_path'   => $keyPath,
                    'error'      => $e->getMessage(),
                ]);
                $project->delete();
                $projectDetail->delete();
                toastr()->error('Cloud upload failed.');
                return back()->withErrors(['video' => 'Failed to upload video to cloud storage.'])->withInput();
            }

            // Simpan informasi HW
            Hwinfo::create([
                'user_id'    => $user->id,
                'project_id' => $project->id,
                'is_success' => false,
            ]);

            // Kirim ke Pub/Sub
            try {
                $payload = json_encode([
                    'user_id'            => $user->id,
                    'id'                 => $project->id,
                    'project_details_id' => $projectDetail->id,
                    'project_id_env'     => env('PROJECT_ID'),
                    'topic_id_env'       => env('TOPIC_ID'),
                ], JSON_THROW_ON_ERROR);

                $isPublished = function_exists('publish_message') ? publish_message($payload) : false;

                if ($isPublished) {
                    Log::info('Pub/Sub message published', [
                        'project_id'  => $project->id,
                        'gcp_project' => env('PROJECT_ID'),
                        'topic'       => env('TOPIC_ID')
                    ]);
                } else {
                    Log::warning('Pub/Sub message not published', [
                        'project_id'  => $project->id,
                        'gcp_project' => env('PROJECT_ID'),
                        'topic'       => env('TOPIC_ID')
                    ]);
                }
            } catch (JsonException $e) {
                Log::error('JSON encode error before Pub/Sub publish', [
                    'project_id' => $project->id,
                    'error'      => $e->getMessage()
                ]);
            } catch (Throwable $e) {
                Log::error('Failed to publish message to Pub/Sub', [
                    'project_id'  => $project->id,
                    'gcp_project' => env('PROJECT_ID'),
                    'topic'       => env('TOPIC_ID'),
                    'error'       => $e->getMessage()
                ]);
            }

            toastr()->success("Video uploaded, processing has started.");
            return back();
        } catch (ValidationException $e) {
            Log::warning('Validation failed during upload', ['errors' => $e->errors()]);
            toastr()->error($e->errors()['video'][0] ?? 'Validation failed. Please check your input.');
            return back()->withErrors($e->errors())->withInput();
        } catch (QueryException $e) {
            Log::error('Database error during upload', ['error' => $e->getMessage()]);
            toastr()->error('Database error occurred during upload.');
            return back()->withInput();
        } catch (Throwable $e) {
            Log::error('Unexpected upload error', ['error' => $e->getMessage()]);
            toastr()->error('Unexpected error occurred during upload.');
            return back()->withInput();
        }
    }

    private function resolveLimitsForRole(string $role): array
    {
        $role = in_array($role, [self::ROLE_FREE, self::ROLE_PLUS, self::ROLE_PRO], true) ? $role : self::ROLE_FREE;
        $config = config("files.upload.plans.{$role}", config('files.upload.plans.free'));

        $maxProjects = max((int) ($config['limit'] ?? 0), 0);
        $maxFileMb   = max((int) ($config['max_file_mb'] ?? 1), 1);

        return [
            'maxProjects' => $maxProjects,
            'maxFileMb'   => $maxFileMb,
        ];
    }
}
