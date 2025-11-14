<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class AnalyticsDetails extends Component
{
    public $projectId;

    public $project;

    // Videos
    public $video_object_detection_Url;
    public $video_player_keypoints_Url;

    // Heatmap Player
    public $minimapUrl;
    public $videoHeatmapUrl;
    public $imageHeatmapUrl;
    public $text_heatmap;

    // Ball Droppings
    public $videoBalldroppingsUrl;
    public $balldropUrl;
    public $imageHeatmapBalldroppingsUrl;
    public $text_balldrop;

    // Stats
    public $forehand;
    public $backhand;
    public $serve;
    public $ready;
    public $maxValue;

    public $videoDuration;
    public $processingTime;

    // Tabs
    public $videoTab = 'minimap';   // minimap | heatmapvideo
    public $ballTab = 'minimap';       // minimap | heatmap

    public $isHeatmap = false;
    public $isBallHeatmap = false;

    protected $listeners = ['project-updated' => 'refreshProject'];

    public function mount($projectId)
    {
        $this->projectId = $projectId;
        $this->loadProject();
    }

    public function refreshProject()
    {
        $this->loadProject();
    }

    private function loadProject()
    {
        try {
            $project = Project::with('projectDetails')
                ->where('user_id', Auth::id())
                ->findOrFail($this->projectId);

            $detail = $project->projectDetails;

            $formatTime = function ($seconds) {
                if (is_null($seconds)) return '00:00:00';
                $h = floor($seconds / 3600);
                $m = floor(($seconds % 3600) / 60);
                $s = $seconds % 60;
                return sprintf('%02d:%02d:%02d', $h, $m, $s);
            };

            $this->project = $project;

            // Player & Object Detection video
            $this->video_object_detection_Url = $detail->link_video_object_detections ?? null;
            $this->video_player_keypoints_Url = $detail->link_video_player_keypoints ?? null;

            // Heatmap Player
            $this->minimapUrl = $detail->link_video_minimap_player ?? null;
            $this->videoHeatmapUrl = $detail->link_video_heatmap_player ?? null;
            $this->imageHeatmapUrl = $detail->link_image_heatmap_player ?? null;
            $this->text_heatmap = $detail->genai_heatmap_player_understanding ?? null;

            // Ball Droppings
            $this->videoBalldroppingsUrl = $detail->link_video_ball_droppings ?? null;
            $this->balldropUrl = $detail->link_image_ball_droppings ?? null;
            $this->imageHeatmapBalldroppingsUrl = $detail->link_image_heatmap_ball_droppings ?? null;
            $this->text_balldrop = $detail->genai_ball_droppings_understanding ?? null;

            // Stats
            $this->forehand = $detail->forehand_count ?? 0;
            $this->backhand = $detail->backhand_count ?? 0;
            $this->serve = $detail->serve_count ?? 0;
            $this->ready = $detail->ready_position_count ?? 0;

            $this->maxValue = max([
                $this->forehand,
                $this->backhand,
                $this->serve,
                $this->ready,
                1
            ]);

            $this->videoDuration = $formatTime($detail->video_duration ?? 0);
            $this->processingTime = $formatTime($detail->video_processing_time ?? 0);


        } catch (ModelNotFoundException $e) {

            Log::warning('Project not found in AnalyticsDetails Livewire', [
                'user_id' => Auth::id(),
                'project_id' => $this->projectId
            ]);

            $this->project = null;

        } catch (\Throwable $e) {

            Log::error('AnalyticsDetails Livewire error loading project', [
                'user_id' => Auth::id(),
                'project_id' => $this->projectId,
                'error' => $e->getMessage()
            ]);

            $this->project = null;
        }
    }

    public function render()
    {
        return view('livewire.analytics-details');
    }



    public function updatedIsHeatmap($value)
    {
        $this->videoTab = $value ? 'heatmapvideo' : 'minimap';
    }


    public function updatedIsBallHeatmap($value)
    {
        $this->ballTab = $value ? 'heatmap' : 'minimap';
    }
    }
