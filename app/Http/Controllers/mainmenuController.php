<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\IncidentTypes;

class mainmenuController extends Controller
{

    /** ------------------------ Incident Types --------------------------- */
    public function incident_types_view(){
        return view ('admin.incident_types');
    }

    public function add_incident_type(Request $request){
        $validated = $request->validate([
            'add_incident' => 'required|string|max:255', // This works for indexed array as well
        ]);
    
        $user_id = Auth()->user()->id;
    
        foreach ($validated['add_incident'] as $incident_name) {
            $incident = new IncidentTypes;
            $incident->cases = $incident_name;
            $incident->created_by = $user_id;
            $incident->updated_by = $user_id;
            $incident->save();
        }
    
        return response()->json(['success' => 'Incident Type Added Successfully']);
    }
    
    /** ------------------------ ./ Incident Types --------------------------- */

}
