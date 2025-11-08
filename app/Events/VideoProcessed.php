<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;


class VideoProcessed implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $projectId;
    public $userId;

    public function __construct($projectId, $userId)
    {
        $this->projectId = $projectId;
        $this->userId = $userId;
    }

    // 🟢 channel publik unik per user
    public function broadcastOn()
    {
        return new Channel('user-updates.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'VideoProcessed';
    }
}
