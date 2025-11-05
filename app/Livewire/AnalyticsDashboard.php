<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;

class AnalyticsDashboard extends Component
{
    public $search = '';
    public $sort = 'newest';
    public $projects = [];
    public $projectCount = 0;
    public $videoInProcessCount = 0;
    public $videoDoneCount = 0;
    public $maxLimit = 10;
    public $percentageUsed = 0;
    public $maxUploadMb = 50;
    public $role = 'free';
    public $planLabel = 'Free';

    protected $listeners = ['project-updated' => 'refreshProjects'];

    public function getListeners()
    {
        return [
            "echo:project-updates,VideoProcessed" => 'onVideoProcessed',
        ];
    }

    public function onVideoProcessed($payload)
    {
        $this->loadProjects();

        $this->dispatch('showToastr', [
            'type' => 'success',
            'message' => '🎾 Project finished processing! (ID: ' . $payload['projectId'] . ')',
        ]);
    }

    public function mount()
    {
        $user = Auth::user();
        $this->role = strtolower(optional($user)->role ?? 'free');
        $this->planLabel = ucfirst($this->role);
        $this->maxLimit = $user->max_projects ?? 10;
        $this->maxUploadMb = $user->max_upload_mb ?? 50;
        $this->loadProjects();
    }

    public function refreshProjects()
    {
        $this->loadProjects();
    }

    public function updatedSearch()
    {
        $this->loadProjects();
    }

    public function updatedSort()
    {
        $this->loadProjects();
    }

    private function loadProjects()
    {
        $query = Project::where('user_id', Auth::id());

        if ($this->search) {
            $query->where('project_name', 'like', '%' . $this->search . '%');
        }

        switch ($this->sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'done':
                $query->where('is_mailed', true)->orderBy('created_at', 'desc');
                break;
            case 'inprocess':
                $query->where('is_mailed', false)->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $this->projects = $query->get();
        $this->projectCount = Project::where('user_id', Auth::id())->count();
        $this->videoInProcessCount = Project::where('user_id', Auth::id())->where('is_mailed', false)->count();
        $this->videoDoneCount = Project::where('user_id', Auth::id())->where('is_mailed', true)->count();
        $this->percentageUsed = $this->maxLimit > 0
            ? round(($this->projectCount / $this->maxLimit) * 100, 1)
            : 0;
    }

    public function render()
    {
        $remaining = max(0, $this->maxLimit - $this->projectCount);
        return view('livewire.analytics-dashboard', [
            'remaining' => $remaining,
        ]);
    }
}
