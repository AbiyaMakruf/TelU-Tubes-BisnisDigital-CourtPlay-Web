<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Events\VideoProcessed;
use Illuminate\Support\Facades\Log;

class ProjectUpdateController extends Controller
{
    public function callback(Request $request)
    {
        try {
            // ✅ Validasi payload JSON
            $validated = $request->validate([
                'project_id' => ['required', 'uuid'],
                'status'     => ['required', 'string', 'in:done,processing,failed'],
                'x-api-key'  => ['required', function ($attribute, $value, $fail) {
                    $expectedKey = config('services.project_update.api_key', env('PROJECT_UPDATE_API_KEY'));
                    if ($value !== $expectedKey) {
                        $fail('Invalid API key.');
                    }
                }],
            ]);

            Log::info('📩 Pub/Sub callback received', ['payload' => $validated]);

            // Ambil project_id dan status dari hasil validasi
            $projectId = $validated['project_id'];
            $status    = $validated['status'];

            // --- (2) Cari project di database ---
            $project = Project::find($projectId);

            if (!$project) {
                Log::warning('⚠️ Project not found', ['project_id' => $projectId]);
                return response()->json([
                    'success' => false,
                    'message' => 'Project not found.'
                ], 404);
            }

            // --- (3) Update status project ---
            if ($status === 'done') {
                $project->update(['is_mailed' => true]);
            }

            // Kirim event ke frontend
            event(new VideoProcessed($project->id));

            Log::info('✅ Broadcasted VideoProcessed event', [
                'project_id' => $project->id,
                'status' => $status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Project status updated & broadcasted successfully.',
                'project_id' => $project->id,
                'status' => $status
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payload.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Throwable $e) {
            Log::error('❌ Project update callback error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}

