<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectDetail;
use App\Models\Hwinfo;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UploadController extends Controller
{
    const MAX_UPLOAD_LIMIT = 100;

    public function index()
    {
        $user = auth()->user();
        $projectCount = $user->projects()->count();
        $remainingQuota = self::MAX_UPLOAD_LIMIT - $projectCount;

        return view('uploads', [
            'projectCount' => $projectCount,
            'maxLimit' => self::MAX_UPLOAD_LIMIT,
            'remainingQuota' => $remainingQuota,
            'hasReachedLimit' => $remainingQuota <= 0,
        ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $projectCount = $user->projects()->count();

        if ($projectCount >= self::MAX_UPLOAD_LIMIT) {
            return back()->withErrors([
                'video' => 'You have reached your maximum video analysis limit (100 projects). Please contact support for an upgrade.'
            ])->withInput();
        }

        $request->validate([
            'video' => 'required|mimes:mp4,mov,avi|max:2048000', // 2GB
            'project_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $projectDetail = ProjectDetail::create([
            'description' => $request->input('description'),
        ]);

        $project = Project::create([
            'user_id' => $user->id,
            'project_details_id' => $projectDetail->id,
            'project_name' => $request->input('project_name'),
            'upload_date' => now(),
        ]);

        $file = $request->file('video');
        $originalName = $file->getClientOriginalName();
        $localFilePath = $file->getPathname();

        $objectName = "uploads/videos/{$user->id}/{$project->id}/{$originalName}";
        $bucket = 'courtplay-storage';
        $keyFile = storage_path('app/keys/courtplay-gcs-key.json');

        try {
            $publicUrl = upload_object($bucket, $objectName, $localFilePath, $keyFile);
            if (file_exists($localFilePath)) {
                @unlink($localFilePath);
            }

            $projectDetail->update([
                'link_video_original' => $publicUrl,
            ]);

        } catch (\Exception $e) {
            Log::error('GCS Upload failed: ' . $e->getMessage());
            return back()->withErrors(['video' => 'Failed to upload video to cloud storage.'])->withInput();
        }

        Hwinfo::create([
            'user_id'    => $user->id,
            'project_id' => $project->id,
            'is_success' => false,
        ]);

        try {
            $payload = json_encode([
                'user_id'            => $user->id,
                'project_id'         => $project->id,
                'project_details_id' => $projectDetail->id,
            ], JSON_THROW_ON_ERROR);

            $isPublished = publish_message($payload);

            if ($isPublished) {
                Log::info("Pub/Sub message published for project {$project->id}");
            } else {
                Log::warning("Pub/Sub message NOT published for project {$project->id}");
            }
        } catch (\JsonException $e) {
            Log::error("JSON encode error before Pub/Sub publish: " . $e->getMessage());
        } catch (\Throwable $e) {
            Log::error("Failed to publish message to Pub/Sub: " . $e->getMessage());
        }


        $remainingQuota = self::MAX_UPLOAD_LIMIT - $projectCount;

        return view('uploads', [
            'projectCount' => $projectCount,
            'maxLimit' => self::MAX_UPLOAD_LIMIT,
            'remainingQuota' => $remainingQuota,
            'hasReachedLimit' => $remainingQuota <= 0,
        ]);
    }

}
