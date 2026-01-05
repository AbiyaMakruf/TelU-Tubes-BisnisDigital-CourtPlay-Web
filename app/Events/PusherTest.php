<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PusherTest implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;

    public function __construct($message = 'Hello from Laravel!')
    {
        $this->message = $message;
    }

    public function broadcastOn()
    {
        // nama channel publik
        return new Channel('test-channel');
    }

    public function broadcastAs()
    {
        // nama event di sisi frontend
        return 'PusherTest';
    }
}
