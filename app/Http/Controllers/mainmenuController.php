<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentTypes;
use App\Models\User;
use App\Models\responseTeam_model;
use App\Models\rtMembers_model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class mainmenuController extends Controller
{

    /** ------------------------ Incident Types --------------------------- */
    public function incident_types_view(){
        $incident_types = IncidentTypes::with(['createdByUser:id,name', 'updatedByUser:id,name'])
        ->select('id', 'cases', 'created_by', 'updated_by', 'created_at', 'updated_at')
        ->orderBy('created_at', 'desc')
        ->paginate(5);
    
        return view('admin.incident_types', compact('incident_types'));
    }

    public function add_incident_type(Request $request){

       // First, decode the JSON string from the 'incidents' form field
        $incidents = json_decode($request->input('incidents'), true);

        // You might want to validate the decoded array as well, depending on your needs
        if (!$incidents || !is_array($incidents)) {
            return response()->json(['error' => 'Invalid incidents provided'], 422);
        }

        $user_id = Auth()->user()->id;

        try {
            foreach ($incidents as $incident_name) {
                $incident = new IncidentTypes;
                $incident->cases = $incident_name;
                $incident->created_by = $user_id;
                $incident->updated_by = $user_id;
                $incident->save();
            }
            return response()->json(['success' => 'Incident Type Added Successfully']);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to add incident type: ' . $e->getMessage()], 500);
        }
        }

    public function fetchIncidentTbl(){
        $incident_types = IncidentTypes::with(['createdByUser:id,name', 'updatedByUser:id,name'])
        ->select('id', 'cases', 'created_by', 'updated_by', 'created_at', 'updated_at')
        ->orderBy('created_at', 'desc')
        ->paginate(5);
    
    // Return only the table body as HTML
    return view('partials.incident_types_table', compact('incident_types'))->render();
    }

    public function deleteIncidentType($id){
        $incident = IncidentTypes::find($id);
        if ($incident) {
        $incident->delete();
        return response()->json(['success' => 'Incident Type Deleted Successfully']);
    } else {
        return response()->json(['error' => 'Failed to Delete incident type: ']);
    }
    }


    public function getIncidentType($id){
        $incident = IncidentTypes::findOrFail($id);
        return response()->json($incident);
    }

    public function updateIncidentType(Request $request, $id) {

        try{
        $incident = IncidentTypes::findOrFail($id);
        $incident->cases = $request->input('cases');
        $incident->updated_by = Auth()->user()->id;
        $incident->save();
    
        return response()->json(['success' => 'Incident Type Updated Successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to update incident type: ' . $e->getMessage()], 500);
    }
    }
    /** ------------------------ ./ Incident Types --------------------------- */

    /**-------------------------- Response Team ------------------------ */
    public function reports_view(){
        return view('admin.reports');
    }

    public function teams_view(){
        $users = User::select('id', 'name')->get();
        $teams = responseTeam_model::select('id', 'team_name')->get();

        $rtmems = rtMembers_model::with(['createdByUser:id,name', 'teamRefTeams:id,team_name', 'member:id,name'])
        ->select('id', 'team_id', 'member_id', 'created_by', 'status', 'created_at')
        ->orderBy('created_at', 'desc')
        ->paginate(2);

        $forteams = responseTeam_model::with(['createdByUser:id,name', 'updatedByUser:id,name'])
                    ->select('id', 'team_name', 'created_by', 'updated_by', 'created_at', 'updated_at')
                    ->orderBy('created_at', 'desc')
                    ->paginate(2);

        return view('admin.responseTeams', compact('users', 'teams', 'rtmems', 'forteams'));
    }

    public function fetchTeamsMembersTbl(){
        $rtmems = rtMembers_model::with(['createdByUser:id,name', 'teamRefTeams:id,team_name', 'member:id,name'])
        ->select('id', 'team_id', 'member_id', 'created_by', 'status', 'created_at')
        ->orderBy('created_at', 'desc')
        ->paginate(2);

        return view('partials.responseTeams_table', compact('rtmems'))->render();
    }


    public  function fetchTeamsTbl(){
        $forteams = responseTeam_model::with(['createdByUser:id,name', 'updatedByUser:id,name'])
        ->select('id', 'team_name', 'created_by', 'updated_by', 'created_at', 'updated_at')
        ->orderBy('created_at', 'desc')
        ->paginate(2);

        return view('partials.teams_table', compact('forteams'))->render();
    }


    //adding team START
    public function add_rteams(Request $request){
        $teams = json_decode($request->input('teams'), true);

         // You might want to validate the decoded array as well, depending on your needs
         if (!$teams || !is_array($teams)) {
            return response()->json(['error' => 'Invalid teams provided'], 422);
        }

        $user_id = Auth()->user()->id;

        try{
            foreach ($teams as $team_name) {
                $team = new responseTeam_model;
                $team->team_name = $team_name;
                $team->created_by = $user_id;
                $team->updated_by = $user_id;
                $team->save();
            }
            return response()->json(['success' => 'Response Team Added Successfully']);
         } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add response team: ' . $e->getMessage()], 500);
        }
    }
    // adding team END

    //adding team member START
    public function add_teammember (Request $request){
        $teamId = $request->input('team');
        $members = json_decode($request->input('members'), true);

        // You might want to validate the decoded array as well, depending on your needs
        if (!$members || !is_array($members)) {
            return response()->json(['error' => 'Invalid members provided'], 422);
        }
        try{
            foreach ($members as $memberId) {
                rtMembers_model::create([
                    'team_id' => $teamId,
                    'member_id' => $memberId,
                    'created_by' => auth()->user()->id, // Example: Setting the current logged-in user as the creator
                    'updated_by' => auth()->user()->id,
                ]);
            }
            
            return response()->json(['success' => 'Members added successfully.']);
        } catch (\Exception $e) {
        return response()->json(['error' => 'Failed to add team member/s: ' . $e->getMessage()], 500);
    }
}
    //adding team meber END

    
    //delete member START
    public function deleteRTmember($id){
        $del_rt_member = rtMembers_model::find($id);
        if ($del_rt_member) {
            $del_rt_member->delete();
            return response()->json(['success' => 'Team Disbanded Successfully']);
        } else {
            return response()->json(['error' => 'Failed to Disband Team: ']);
        }
    }
    //delete member END

    //delete team START
    public function deleteTeams($id){
        $del_teams = responseTeam_model::find($id);
        if ($del_teams) {
            $del_teams->delete();
            return response()->json(['success' => 'Team Deleted Successfully']);
        } else {
            return response()->json(['error' => 'Failed to Delete Team: ']);
        }
    }
    //delete team END

    //for edit team
    public function getTeamID($id){
        $teamid = responseTeam_model::findOrFail($id);
        return response()->json($teamid);
    }

    public function updateTeam(Request $request, $id) {
        try{
            $team = responseTeam_model::findOrFail($id);
            $team->team_name = $request->input('team_name');
            $team->updated_by = Auth()->user()->id;
            $team->save();
        
            return response()->json(['success' => 'Team Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update team: ' . $e->getMessage()], 500);
        }
    }
    //for edit team


    public function getRTmemberID($id){
        $rt_member = rtMembers_model::findOrFail($id);
        return response()->json($rt_member);
    }
/**-------------------------- ../Response Team ------------------------ */
}
