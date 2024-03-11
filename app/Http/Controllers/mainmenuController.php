<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentTypes;
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

}
