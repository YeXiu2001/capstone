<?php

namespace App\Http\Controllers;

use App\Models\incident_reports;
use Illuminate\Http\Request;
use App\Models\IncidentTypes;
use App\Models\User;
use App\Models\responseTeam_model;
use App\Models\rtMembers_model;
use App\Models\IncidentDeploymentModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
class mainmenuController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-incident_types', ['only' => ['incident_types_view']]);
        $this->middleware('permission:write-incident_types', ['only' => ['add_incident_type', 'deleteIncidentType', 'getIncidentType', 'updateIncidentType']]);
        $this->middleware('permission:readAndwrite-reports', ['only' => ['reports_view']]);
        $this->middleware('permission:view-responseTeam', ['only' => ['teams_view', 'fetchTeamsTbl', 'fetchTeamsOptions', 'getTeamID']]);
        $this->middleware('permission:write-responseTeam', ['only' => ['add_rteams', 'deleteTeams', 'updateTeam']]);
        $this->middleware('permission:view-members', ['only' => ['teams_view', 'fetchTeamsMembersTbl', 'getRTmemberID']]);
        $this->middleware('permission:write-members', ['only' => ['add_teammember', 'deleteRTmember']]);
        $this->middleware('permission:readAndwrite-routing', ['only' => ['routingView']]);
        $this->middleware('permission:read-manageusers', ['only' => ['manageUsers', 'citizenstbl']]);
    }

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

/** ------------------- Reports ---------------------------------- */
    public function reports_view(){
        $incident_types = IncidentTypes::select('id', 'cases')->get();
        $available_RTeams = responseTeam_model::select('id', 'team_name')
                            ->where('status', 'available')
                            ->get();
        $rteams = responseTeam_model::select('id', 'team_name', 'status')
                    ->where('status', 'available')
                    ->orWhere('status', 'busy')
                    ->orderBy('status', 'asc')
                    ->paginate(3);

        return view('admin.reports', compact('incident_types', 'available_RTeams', 'rteams'));
    }

    public function fetchKanbanData() {
        $kanbanIncidents = incident_reports::with(['modelref_incidenttype', 'deployments.deployedRteam', 'deployments.deployedBy'])
                            ->whereDate('created_at', Carbon::today())
                            ->orderBy('created_at', 'asc')
                            ->get();
    
        // Convert to a JSON-friendly structure
        $kanbanIncidents = $kanbanIncidents->map(function($report) {
            return [
                'id' => $report->id,
                'status' => $report->status,
                'reporter' => $report->reporter,
                'contact' => $report->contact,
                'created_at' => $report->created_at->format('d-m-Y'),
                'address' => $report->address,
                'eventdesc' => $report->eventdesc ?? 'No Description Provided',
                'case_type' => $report->modelref_incidenttype->cases ?? 'N/A',
                'image_url' => $report->imagedir ? asset('images/' . $report->imagedir) : null,
                'deployments' => [
                    'teams' => $report->deployments->pluck('deployedRteam.team_name')->unique()->toArray(),
                    'deployedBy' => optional($report->deployments->first())->deployedBy->name ?? 'N/A',
                ],
            ];
        });
    
        return response()->json($kanbanIncidents);
    }

    public function  getAvailableTeams(){
        $available_RTeams = responseTeam_model::select('id', 'team_name')
                            ->where('status', 'available')
                            ->get();
        return response()->json(['available_RTeams' => $available_RTeams]);
    }

    public function getDeploymentDetails(Request $request)
{
    $reportId = $request->reportId;
    $teamIds = $request->teamIds; // Assuming this comes as an array

    $deployments = IncidentDeploymentModel::with(['deployedRteam', 'deployedBy'])
        ->whereIn('deployed_rteam', $teamIds)
        ->where('incident_id', $reportId)
        ->get();

    $deployedTeams = $deployments->map(function ($deployment) {
        return $deployment->deployedRteam->team_name;
    })->unique();

    $deployedBy = optional($deployments->first())->deployedBy->name ?? 'N/A';

    return response()->json([
        'deployedTeams' => $deployedTeams,
        'deployedBy' => $deployedBy,
    ]);
}

    public function fetchIncidentsforMap()
    {
        $incidentsForMap = incident_reports::with(['modelref_incidenttype'])
        ->where('status', 'pending')
        ->orWhere('status', 'ongoing')
        ->orWhere('status', 'resolved')
        ->whereDate('created_at', Carbon::today())
        ->get(['id', 'reporter', 'contact','lat', 'long', 'eventdesc', 'imagedir', 'incident']);
    
        // Adjusting image URL
        $incidentsForMap->transform(function ($incident) {
            if (!empty($incident->imagedir)) {
                $incident->image_url = asset('images/' . $incident->imagedir); // Using asset() helper to get the full URL
            }
            $incident->case_type = $incident->modelref_incidenttype->cases ?? 'N/A'; // Fallback if relationship is missing
            return $incident;
        });
    
        return response()->json($incidentsForMap);
    }

    public function updateReportKanban(Request $request){
        $report = incident_reports::find($request->reportId);
        if (!$report) {
            return response()->json(['message' => 'Report not found.'], 404);
        }
        $report->status = $request->newStatus;
        $report->save();
        
        $incidentId = $request->reportId;
        $deployed_rteams = $request->input('deployedRteam');
        
        if (!$deployed_rteams || !is_array($deployed_rteams)) {
            return response()->json(['error' => 'No Response Teams Provided'], 422);
        }
    
        $deployedTeamNames = [];
        try {
            foreach ($deployed_rteams as $deployed_rteamId) {
                // Deploy each response team
                IncidentDeploymentModel::create([
                    'incident_id' => $incidentId,
                    'deployed_rteam' => $deployed_rteamId,
                    'deployed_by' => auth()->user()->id,
                ]);
                
                // Update response team status to 'busy'
                $responseTeam = responseTeam_model::find($deployed_rteamId);
                if ($responseTeam) {
                    $responseTeam->status = 'busy';
                    $responseTeam->save();
                    $deployedTeamNames[] = $responseTeam->team_name;
                }
            }
    
            // Get the name of the user who deployed the team
            $deployedByUser = auth()->user()->name;
    
            return response()->json([
                'success' => 'Deployed successfully.',
                'deployedTeams' => $deployedTeamNames,
                'deployedBy' => $deployedByUser
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to Deploy: ' . $e->getMessage()], 500);
        }
    }
    
    public function ongoingToResolved(Request $request){
        $report = incident_reports::find($request->reportId);
        if (!$report) {
            return response()->json(['message' => 'Report not found.'], 404);
        }
        $report->status = $request->newStatus;
        $report->save();
    }
    
    

    public function getPReportDetails($id) {
        $report = incident_reports::find($id);
    
        if ($report) {
            return response()->json($report);
        } else {
            return response()->json(['error' => 'Report not found.'], 404);
        }
    }

    public function updatePReportDetails(Request $request, $id) {
        $report = incident_reports::find($id);
        if (!$report) {
            return response()->json(['message' => 'Report not found.'], 404);
        }
    
        // Validate the request data
        $validated = $request->validate([
            'address' => 'required|string',
            'eventdesc' => 'required|string',
            'incident' => 'required|exists:case_types,id', // Ensure the incident exists
        ]);
    
        // Update the report with validated data
        $report->address = $validated['address'];
        $report->eventdesc = $validated['eventdesc'];
        $report->incident = $validated['incident'];
        $report->save();
    
        return response()->json(['message' => 'Report updated successfully.']);
    }
    
    public function teamstblReports(){
        $rteams = responseTeam_model::select('id', 'team_name', 'status')
                    ->where('status', 'available')
                    ->orWhere('status', 'busy')
                    ->orderBy('status', 'asc')
                    ->paginate(3);
        return view('partials.reports_teamtbl', compact('rteams'))->render();
    }

    public function updateTeamStatus(Request $request, $id) {
        try {
            $team = responseTeam_model::findOrFail($id);
    
            $newStatus = $request->input('status');
    
            // Update only the team's status
            $team->status = $newStatus;
            $team->updated_by = auth()->user()->id; // Log the ID of the user making the change
            $team->save();
    
            return response()->json(['success' => 'Team status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update team status: ' . $e->getMessage()], 500);
        }
    }
    

    public function dismissReportKanban(Request $request){
        $report = incident_reports::find($request->reportId);
        if (!$report) {
            return response()->json(['message' => 'Report not found.'], 404);
        }
        $report->status = $request->newStatus;
        $report->save();
    
        return response()->json(['message' => 'Report dismissed successfully.']);
    }
    
/** ---------------------- ./ Reports ---------------------------------- */



/**-------------------------- Response Team ------------------------ */
    public function teams_view(){
        $allusers = User::select('id', 'name')->get();
        $userswithteam = rtMembers_model::pluck('member_id');

        $vacantusers = User::select('id', 'name')
                    ->whereNotIn('id', $userswithteam)
                    ->get();

        $vacantmembers = $vacantusers->filter(function ($user) {
            return $user->hasPermissionTo('readAndwrite-routing');
            })->values();

        $teams = responseTeam_model::select('id', 'team_name')->get();

        $rtmems = rtMembers_model::with(['createdByUser:id,name', 'updatedByUser:id,name', 'teamRefTeams:id,team_name', 'member:id,name'])
        ->select('id', 'team_id', 'member_id', 'created_by', 'updated_by', 'created_at')
        ->orderBy('created_at', 'desc')
        ->paginate(5);

        $forteams = responseTeam_model::with(['createdByUser:id,name', 'updatedByUser:id,name'])
                    ->select('id', 'team_name', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at')
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

        return view('admin.responseTeams', compact('vacantmembers', 'teams', 'rtmems', 'forteams'));
    }

    public function fetchTeamMembers($teamId) {
        $teamMembers = rtMembers_model::with(['member:id,name'])
                                      ->where('team_id', $teamId)
                                      ->get();
    
        return view('partials.tmembersModal', compact('teamMembers'))->render();
    }
    

    public function fetchTeamsMembersTbl(){
        $rtmems = rtMembers_model::with(['createdByUser:id,name', 'updatedByUser:id,name', 'teamRefTeams:id,team_name', 'member:id,name'])
        ->select('id', 'team_id', 'member_id', 'created_by', 'updated_by', 'created_at')
        ->orderBy('created_at', 'desc')
        ->paginate(5);

        return view('partials.responseTeams_table', compact('rtmems'))->render();
    }


    public  function fetchTeamsTbl(){
        $forteams = responseTeam_model::with(['createdByUser:id,name', 'updatedByUser:id,name'])
                    ->select('id', 'team_name', 'status', 'created_by', 'updated_by', 'created_at', 'updated_at')
                    ->orderBy('created_at', 'desc')
                    ->paginate(5);

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

        public function fetchTeamsOptions(){
            $teams = responseTeam_model::select('id', 'team_name')->get();
            return response()->json(['teams' => $teams]);
        }

        //for edit team
        public function getTeamID($id){
            $teamid = responseTeam_model::findOrFail($id);
            return response()->json($teamid);
        }

        public function updateTeam(Request $request, $id) {
            try{
                $team = responseTeam_model::findOrFail($id);
                $team->team_name = $request->input('team_name');
                $team->status = $request->input('status');
                $team->updated_by = Auth()->user()->id;
                $team->save();
            
                return response()->json(['success' => 'Team Updated Successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to update team: ' . $e->getMessage()], 500);
            }
        }
        //for edit team

        public function getRTmemberID($id){
            try {
                $rt_member = rtMembers_model::with(['createdByUser:id,name', 'teamRefTeams:id,team_name', 'member:id,name'])
                            ->findOrFail($id);
                return response()->json($rt_member);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to fetch RT member: ' . $e->getMessage()], 500);
            }
        }
/**-------------------------- ../Response Team ------------------------ */


/** ------------------------ Routing -------------------------------- */
public function routingView(){
    return view('admin.routing');
}
/** ----------------------- ./Routing ------------------------------- */

/** ------------------------ Manage Users -------------------------- */
public function manageUsers(){
    $pending_users = User::select('id', 'name', 'email', 'contact', 'created_at', 'status')
            ->where('status', 'pending')
            ->orderBy('created_at', 'asc')
            ->paginate(5);
    
    return view('admin.manage_users', compact('pending_users'));
}

public function allcitizens() {
    // Query to fetch users with the 'Citizens' role
    $users = User::select('id', 'name', 'email', 'contact', 'status')
        ->whereHas('roles', function ($query) {
            $query->where('name', 'Citizens');
        })
        ->orderBy('created_at', 'asc')
        ->paginate(10); // Paginate directly from the query

    return view('admin.allcitizens', compact('users'));
}

public function allcitizenstbl() {
    // Query to fetch users with the 'Citizens' role
    $users = User::select('id', 'name', 'email', 'contact', 'status')
        ->whereHas('roles', function ($query) {
            $query->where('name', 'Citizens');
        })
        ->orderBy('created_at', 'asc')
        ->paginate(10); // Paginate directly from the query

    return view('partials.allcitstbl', compact('users'))->render(); 
}

public function searchCitizens(Request $request) {
    $query = $request->input('query');

    // Search users with the specified role and name/email containing the query
    $users = User::select('id', 'name', 'email', 'contact', 'status')
        ->whereHas('roles', function ($q) {
            $q->where('name', 'Citizens');
        })
        ->where(function ($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%');
        })
        ->orderBy('created_at', 'asc')
        ->paginate(10);

    return view('partials.allcitstbl', compact('users'))->render();
}

public function deleteUser($userId) {
    $user = User::find($userId);

    if (!$user) {
        return response()->json(['error' => 'User not found.'], 404);
    }

    // Delete the user record from the database
    $user->delete();

    return response()->json(['success' => 'User deleted successfully']);
}



public function citizenstbl(){
    $pending_users = User::select('id', 'name', 'email', 'contact', 'created_at', 'status')
            ->where('status', 'pending')
            // ->whereHas('permissions', function ($query) {
            //     $query->whereIn('name', ['read-userHome', 'write-userHome']);
            // })
            ->orderBy('created_at', 'asc')
            ->paginate(5);
    
    return view('partials.manageuserstbl', compact('pending_users'))->render();
}

public function searchPendingUsers(Request $request) {
    $query = $request->input('query');

    $pending_users = User::select('id', 'name', 'email', 'contact', 'created_at', 'status')
        ->where('status', 'pending')
        ->where(function($q) use ($query) {
            $q->where('name', 'like', '%' . $query . '%')
                ->orWhere('email', 'like', '%' . $query . '%')
                ->orWhere('contact', 'like', '%' . $query . '%');
        })
        ->orderBy('created_at', 'asc')
        ->paginate(5);

    return view('partials.manageuserstbl', compact('pending_users'))->render();
}


public function approveUser($userId)
{
    try {
        $user = User::findOrFail($userId);
        $user->status = 'approved';
        $user->save();

        // Assign "Citizens" role to the user
        $role = Role::where('name', 'Citizens')->firstOrFail();
        $user->assignRole($role);

        return response()->json(['message' => 'User approved successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong.'], 500);
    }
}

public function rejectUser($userId)
{
    try {
        $user = User::findOrFail($userId);
        $user->status = 'declined';
        $user->save();


        return response()->json(['message' => 'User Rejected successfully.'], 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Something went wrong.'], 500);
    }
}

public function getUserDetails(User $user) // Utilizing route model binding
    {
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'contact' => $user->contact,
            'id_card' => $user->id_card, 
        ]);
    }
/** ------------------------ ./Manage Users -------------------------- */

public function profileView() {
    $userId = auth()->user()->id;
    $user = User::find($userId);

    return view('layouts.app', compact('user')); // Pass the user directly
}

public function updateProfile(Request $request) {
    $userId = auth()->user()->id;
    $user = User::findOrFail($userId);

    // Validate request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
        'new_password' => 'nullable|string|min:8',
        'confirm_password' => 'nullable|string|min:8',
    ]);

    // Update name and email
    $user->name = $request->input('name');
    $user->email = $request->input('email');

    // Handle password update
    if ($request->filled('new_password')) {
        $newPassword = $request->input('new_password');
        $confirmPassword = $request->input('confirm_password');

        if ($newPassword === $confirmPassword) {
            $user->password = Hash::make($newPassword);
        } else {
            return redirect()->back()->withInput()->withErrors(['confirm_password' => 'The new password confirmation does not match.']);
        }
    }

    $user->save();

    return redirect()->back()->with('success', 'Profile updated successfully');
}

public function allReportsView() {
    $query = incident_reports::with(['modelref_incidenttype'])
                ->orderBy('created_at', 'desc');


    $reports = $query->paginate(10); 

    return view('admin.allreports', compact('reports'));
}

public function allReportsTbl()
{
    $query = incident_reports::with(['modelref_incidenttype'])
                ->orderBy('created_at', 'desc');

    $reports = $query->paginate(10);

    return view('partials.allrepstbl', compact('reports'))->render();
}

public function searchReports(Request $request) {
    $query = $request->input('query');

    $reports = incident_reports::with(['modelref_incidenttype'])
        ->where(function ($q) use ($query) {
            $q->where('reporter', 'like', '%' . $query . '%')
                ->orWhere('contact', 'like', '%' . $query . '%')
                ->orWhere('address', 'like', '%' . $query . '%')
                ->orWhere('eventdesc', 'like', '%' . $query . '%')
                ->orWhereHas('modelref_incidenttype', function ($q) use ($query) {
                    $q->where('cases', 'like', '%' . $query . '%');
                });
        })
        ->orderBy('created_at', 'desc')
        ->paginate(10);

    return view('partials.allrepstbl', compact('reports'))->render();
}

}
