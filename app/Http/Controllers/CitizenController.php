<?php

namespace App\Http\Controllers;

use App\Events\AddReport;
use App\Events\RoutingResolve;
use Illuminate\Http\Request;
use App\Models\IncidentTypes;
use App\Models\incident_reports;
use App\Models\User;
use App\Models\responseTeam_model;
use App\Models\rtMembers_model;
use App\Models\IncidentDeploymentModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;

class CitizenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:create-report', ['only' => ['add_incident_report']]);
        $this->middleware('permission:read-userHome', ['only' => ['index']]);
        // $this->middleware('permission:write-userHome', ['only' => ['index']]);
    }

    public function index()
    {
        // $LoggedInUser = auth()->user()->name;
        $incident_types = IncidentTypes::select('id', 'cases')->get();
        return view('citizens.index', compact('incident_types'));
    }
 
    // Add incident report START
    public function add_incident_report(Request $request){
        $request->validate([

            'lat' => 'required',
            'long' => 'required',
            'address' => 'required',
            'incident' => 'required',
            'eventdesc' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);
        
        $lat = $request->input('lat');
        $long = $request->input('long');
        $incidentType = $request->input('incident');
    
        $recentIncidents = DB::table('incidents')
                            ->selectRaw("*, 
                                        ( 3956 * 2 * ASIN(SQRT( POWER(SIN((? - abs(incidents.lat)) * pi()/180 / 2), 2) + COS(? * pi()/180 ) * COS(abs(incidents.lat) * pi()/180) * POWER(SIN((? - incidents.long) * pi()/180 / 2), 2) )) ) as distance",
                                        [$lat, $lat, $long] )
                            ->where('incident', $incidentType)
                            ->where('created_at', '>', Carbon::now()->subMinutes(10))
                            ->having('distance', '<', 0.00621371) // Adjusted for 10 meters
                            ->count();

                    if ($recentIncidents > 0) {
                        return response()->json([
                            'error' => 'A similar incident has been reported nearby within the last 10 minutes.'
                        ], 400); // Or another suitable status code
                    }

        try {
            $reporter = auth()->user()->name;
            $contact = auth()->user()->contact;
            $lat = $request->input('lat');
            $long = $request->input('long');
            $address = $request->input('address');
            $incident = $request->input('incident');
            $eventdesc = $request->input('eventdesc');
            $image = $request->file('image');
            $imageName = time().'.'.$image->extension();
            $image->move(public_path('images'), $imageName);
            
            $incident_report = new incident_reports;
            $incident_report->reporter = $reporter;
            $incident_report->contact = $contact;
            $incident_report->address = $address;
            $incident_report->lat = $lat;
            $incident_report->long = $long;
            $incident_report->incident = $incident;
            $incident_report->eventdesc = $eventdesc;
            $incident_report->imagedir = $imageName;
            $incident_report->save();

            event(new AddReport($incident_report));
            return response()->json(['success' => 'Incident Report Added Successfully']);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to add incident report: ' . $e->getMessage()], 500);
        }
    }
    //add Incident Report END

    public function fetchIncidentsforMapCitizens()
    {
        $incidentsForMap = incident_reports::with(['modelref_incidenttype'])
        ->whereDate('created_at', Carbon::today())
        ->whereIn('status', ['Pending', 'Ongoing'])
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
}
