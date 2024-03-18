<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentTypes;
use App\Models\incident_reports;
class CitizenController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // $LoggedInUser = auth()->user()->name;
        $incident_types = IncidentTypes::select('id', 'cases')->get();
        return view('citizens.index', compact('incident_types'));
    }

    public function add_incident_report(Request $request){
        $request->validate([

            'lat' => 'required',
            'long' => 'required',
            'address' => 'required',
            'incident' => 'required',
            'eventdesc' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

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

        try {
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
            return response()->json(['success' => 'Incident Report Added Successfully']);
        } catch (\Exception $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Failed to add incident report: ' . $e->getMessage()], 500);
        }
    }
}
