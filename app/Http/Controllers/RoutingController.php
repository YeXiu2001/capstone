<?php

namespace App\Http\Controllers;

use App\Events\RoutingResolve;
use App\Models\incident_reports;
use Illuminate\Http\Request;
use App\Models\IncidentTypes;
use App\Models\User;
use App\Models\responseTeam_model;
use App\Models\rtMembers_model;
use App\Models\IncidentDeploymentModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

class RoutingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:readAndwrite-routing', ['only' => ['routingView']]);
        $this->middleware('permission:readAndwrite-routing', ['only' => ['getAssignedIncidents']]);
        $this->middleware('permission:readAndwrite-routing', ['only' => ['resolveReport']]);
    }

    public function routingView()
    {
        $userId = Auth()->user()->id; // Correctly get the authenticated user
    
        // Assuming a user belongs to a single team
        $teamMember = Auth()->user()->rtMembers->first(); // Get the first rtMembers relationship

        if ($teamMember) {
            $team = $teamMember->teamRefTeams()->select('id', 'team_name', 'status')->first(); // Get the team details
        } else {
            $team = null; // No team found for user
        }

        // Fetch the team ID for the logged-in user
        $teamId = Auth()->user()->rtMembers->first()->teamRefTeams()->value('id');

        if ($teamId) {
            $incidents = incident_reports::with(['modelref_incidenttype', 'deployments'])
                ->whereHas('deployments', function($query) use ($teamId) {
                    $query->where('deployed_rteam', $teamId)
                            ->where('status', 'ongoing');
                })->get();
        } else {
            $incidents = collect();
        }
        return view('rteams.routing', compact('team', 'incidents' ));
    }

    public function getAssignedIncidents(Request $request)
    {
        $userId = Auth()->user()->id; // Correctly get the authenticated user
    
        // Assuming a user belongs to a single team
        $teamMember = Auth()->user()->rtMembers->first(); // Get the first rtMembers relationship

        if ($teamMember) {
            $team = $teamMember->teamRefTeams()->select('id', 'team_name')->first(); // Get the team details
        } else {
            $team = null; // No team found for user
        }

        // Fetch the team ID for the logged-in user
        $teamId = Auth()->user()->rtMembers->first()->teamRefTeams()->value('id');

        if ($teamId) {
            $incidents = incident_reports::with(['modelref_incidenttype', 'deployments'])
                ->whereHas('deployments', function($query) use ($teamId) {
                    $query->where('deployed_rteam', $teamId)
                            ->where('status', 'ongoing');
                })->get();
        } else {
            $incidents = collect();
        }
        return response()->json($incidents, $team);
    }

    public function resolveReport($id){
        $userId = Auth()->user()->id;
        $teamId = Auth()->user()->rtMembers->first()->teamRefTeams()->value('id');

        try{
            $report = incident_reports::findOrFail($id);
            $report->status = 'resolved';
            $report->save();

            $team = responseTeam_model::findOrFail($teamId);
            $team-> status = 'available';
            $team->updated_by = $userId;
            $team->save();
            
            event(new RoutingResolve($report, $team));
            return response()->json(['success' => 'Incident Status Updated Successfully']);
        }catch (\Exception $e) {    
            return response()->json(['error' => 'Failed to update report status: ' . $e->getMessage()], 500);
        }

    }

}
