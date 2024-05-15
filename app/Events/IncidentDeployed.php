<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IncidentDeployed implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incident;
    public $team;

    public function __construct($incident, $team)
    {
        $this->incident = $incident;
        $this->team = $team;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('teams.' . $this->team->id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'incident_id' => $this->incident->id,
            'incident_reporter' => $this->incident->reporter,
            'incident_address' => $this->incident->address,
            'incident_description' => $this->incident->eventdesc,
        ];
    }
}
