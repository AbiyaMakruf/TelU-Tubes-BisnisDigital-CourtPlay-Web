<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Project;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            $sort = $request->get('sort', 'newest');
            $search = $request->get('search');

            $projects = Project::where('user_id', $user->id)
                ->when($search, function ($query, $search) {
                    $query->where('project_name', 'ILIKE', "%{$search}%");
                })
                ->when($sort, function ($query, $sort) {
                    switch ($sort) {
                        case 'oldest': $query->orderBy('upload_date', 'asc'); break;
                        case 'done': $query->orderByDesc('is_mailed'); break;
                        case 'inprocess': $query->orderBy('is_mailed', 'asc'); break;
                        default: $query->orderBy('upload_date', 'desc'); break;
                    }
                })
                ->get();

            $role = strtolower((string) ($user->role ?? 'free'));
            switch ($role) {
                case 'pro':
                    $maxLimit = (int) env('UPLOAD_LIMIT_PRO', 50);
                    $maxUploadMb = (int) env('UPLOAD_MAX_FILE_MB_PRO', 1024);
                    break;
                case 'plus':
                    $maxLimit = (int) env('UPLOAD_LIMIT_PLUS', 200);
                    $maxUploadMb = (int) env('UPLOAD_MAX_FILE_MB_PLUS', 2048);
                    break;
                default:
                    $maxLimit = (int) env('UPLOAD_LIMIT_FREE', 3);
                    $maxUploadMb = (int) env('UPLOAD_MAX_FILE_MB_FREE', 200);
                    break;
            }

            $projectCount = $projects->count();
            $percentageUsed = $maxLimit > 0 ? min(100, ($projectCount / $maxLimit) * 100) : 0;
            $videoInProcessCount = Project::where('user_id', $user->id)->where('is_mailed', false)->count();
            $videoDoneCount = Project::where('user_id', $user->id)->where('is_mailed', true)->count();

            return view('analytics', compact(
                'projects',
                'projectCount',
                'maxLimit',
                'percentageUsed',
                'videoInProcessCount',
                'videoDoneCount',
                'sort',
                'search',
                'maxUploadMb'
            ))->with([
                'currentSort' => $sort,
                'currentSearch' => $search,
            ]);
        } catch (\Throwable $e) {
            Log::error('Analytics index failed', ['user_id' => optional(Auth::user())->id, 'error' => $e->getMessage()]);
            toastr()->error('Failed to load analytics.');
            return back();
        }
    }

    public function show($id)
    {
        try {
            $project = Project::with('projectDetails')->where('user_id', Auth::id())->findOrFail($id);
            $detail = $project->projectDetails;

            $formatTime = function ($seconds) {
                if (is_null($seconds)) return '00:00:00';
                $h = floor($seconds / 3600);
                $m = floor(($seconds % 3600) / 60);
                $s = $seconds % 60;
                return sprintf('%02d:%02d:%02d', $h, $m, $s);
            };

            $maxValue = max([
                $detail->forehand_count ?? 0,
                $detail->backhand_count ?? 0,
                $detail->serve_count ?? 0,
                $detail->ready_position_count ?? 0,
                1
            ]);

            return view('analytics_details', [
                'project' => $project,
                'videoUrl' => $detail->link_video_object_detections ?? null,
                'heatmapUrl' => $detail->link_image_heatmap_player ?? null,
                'forehand' => $detail->forehand_count ?? 0,
                'backhand' => $detail->backhand_count ?? 0,
                'serve' => $detail->serve_count ?? 0,
                'ready' => $detail->ready_position_count ?? 0,
                'maxValue' => $maxValue,
                'videoDuration' => $formatTime($detail->video_duration ?? 0),
                'processingTime' => $formatTime($detail->video_processing_time ?? 0),
            ]);
        } catch (ModelNotFoundException $e) {
            Log::warning('Project not found for show', ['user_id' => Auth::id(), 'project_id' => $id]);
            toastr()->error('Project not found.');
            return redirect()->route('analytics');
        } catch (\Throwable $e) {
            Log::error('Analytics show failed', ['user_id' => Auth::id(), 'project_id' => $id, 'error' => $e->getMessage()]);
            toastr()->error('Failed to load project details.');
            return redirect()->route('analytics');
        }
    }
}
