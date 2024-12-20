<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\incident_reports;
use App\Models\responseTeam_model;

class RoutingResolve implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $incident_report;
    public $team;

    public function __construct(incident_reports $incident_report, responseTeam_model $team)
    {
        $this->incident_report = $incident_report;
        $this->team = $team;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('reports'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'incident_id' => $this->incident_report->id,
            'team_name' => $this->team->team_name,
            'incident_status' => $this->incident_report->status,
        ];
    }
}
