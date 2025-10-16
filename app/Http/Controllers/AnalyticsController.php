<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;


class AnalyticsController extends Controller
{

    // Tambahkan parameter Request untuk menerima input pencarian dan pengurutan
    public function index(Request $request)
    {
        $user = auth()->user();

        $sort = $request->get('sort', 'newest');
        $search = $request->get('search');

        $projects = Project::where('user_id', $user->id)
            ->when($search, function ($query, $search) {
                $query->where('project_name', 'ILIKE', "%{$search}%");
            })
            ->when($sort, function ($query, $sort) {
                switch ($sort) {
                    case 'oldest':
                        $query->orderBy('upload_date', 'asc');
                        break;

                    case 'done':
                        $query->orderByDesc('is_mailed');
                        break;

                    case 'inprocess':
                        $query->orderBy('is_mailed', 'asc');
                        break;

                    default:
                        $query->orderBy('upload_date', 'desc');
                        break;
                }
            })
            ->get();

        //   tambahan
        $projectCount = $projects->count();
        $maxLimit = UploadController::MAX_UPLOAD_LIMIT;
        $percentageUsed = ($projectCount / $maxLimit) * 50;
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
            'search'
        ))->with([
            'currentSort' => $sort,
            'currentSearch' => $search,
        ]);
    }

    public function show($id)
    {
        $project = \App\Models\Project::with('projectDetails')->findOrFail($id);
        $detail = $project->projectDetails;

        $formatTime = function ($seconds) {
            if (is_null($seconds)) return '00:00:00';
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $secs = $seconds % 60;
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        };

        $maxValue = max([
            $detail->forehand_count ?? 0,
            $detail->backhand_count ?? 0,
            $detail->serve_count ?? 0,
            $detail->ready_position_count ?? 0,
            1
        ]);

        $data = [
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
        ];

        return view('analytics_details', $data);
    }


}
