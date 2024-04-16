<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RoutingResolve implements ShouldBroadcastNow
{ 
    use InteractsWithSockets, SerializesModels;

    public $reportId;
    public $teamId;

    public function __construct($reportId, $teamId)
    {
        $this->reportId = $reportId;
        $this->teamId = $teamId;
    }

    public function broadcastOn()
    {
        return new Channel('fromRoutingResolve');
    }

    public function broadcastAs()
    {
        return 'routing-resolved';
    }
}
