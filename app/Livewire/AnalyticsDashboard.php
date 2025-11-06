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
        $user   = Auth::user();
        $sort   = $this->sort ?: 'newest';
        $search = $this->search;

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
                        $query->orderBy('is_mailed', 'desc');
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

        $this->projects = $projects;

        $role         = strtolower((string) ($user->role ?? 'free'));
        $uploadConfig = config("files.upload.plans.{$role}", config('files.upload.plans.free'));

        $this->maxLimit    = (int) ($uploadConfig['limit'] ?? 10);
        $this->maxUploadMb = (int) ($uploadConfig['max_file_mb'] ?? 50);

        $this->projectCount        = $projects->count();
        $this->videoInProcessCount = Project::where('user_id', $user->id)->where('is_mailed', false)->count();
        $this->videoDoneCount      = Project::where('user_id', $user->id)->where('is_mailed', true)->count();

        $this->percentageUsed = $this->maxLimit > 0
            ? min(100, ($this->projectCount / $this->maxLimit) * 50)
            : 0;

    }

    public function render()
    {
        return view('livewire.analytics-dashboard');
    }
}
