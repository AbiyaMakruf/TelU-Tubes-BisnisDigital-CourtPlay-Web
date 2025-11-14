<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoProcessed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    public function broadcastOn()
    {
        return new Channel('project-updates');
    }

    public function broadcastAs()
    {
        return 'VideoProcessed';
    }
}
