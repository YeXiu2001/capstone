<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\incident_reports;
use Illuminate\Support\Facades\DB;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:read-dashboard', ['only' => ['index']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.home');
    }

    public function dashboardMap(Request $request){

        $year = $request->input('year', now()->year);

        $incidentsForMap = incident_reports::with(['modelref_incidenttype'])
            ->get(['id', 'reporter', 'contact','lat', 'long', 'eventdesc', 'imagedir', 'incident']);
    
        // Adjusting image URL
        $incidentsForMap->transform(function ($incident) {
            if (!empty($incident->imagedir)) {
                $incident->image_url = asset('images/' . $incident->imagedir);
            }
            $incident->case_type = $incident->modelref_incidenttype->cases ?? 'N/A';
            return $incident;
        });
    
        $monthlyIncidentCounts = incident_reports::with('modelref_incidenttype')
                            ->select(DB::raw('MONTH(created_at) as month'), 'incident', DB::raw('count(*) as count'))
                            ->whereYear('created_at', $year)
                            ->groupBy('month', 'incident')
                            ->orderBy('month', 'asc')
                            ->get()
                            ->mapToGroups(function ($item) {
                                return [$item->modelref_incidenttype->cases => ['month' => $item->month, 'count' => $item->count]];
                            });


        $availableYears = incident_reports::selectRaw('YEAR(created_at) as year')->distinct()->orderBy('year', 'desc')->pluck('year');

        return response()->json([
            'incidents' => $incidentsForMap,
            'monthlyIncidentCounts' => $monthlyIncidentCounts,
            'availableYears' => $availableYears // Add this line
        ]);
    }

    public function dashboardPie(Request $request){
        $pieYear = $request->input('pieYear', now()->year);

        $incidentCountsForPie = incident_reports::with('modelref_incidenttype')
                        ->selectRaw('count(*) as count, incident')
                        ->whereYear('created_at', $pieYear)
                        ->groupBy('incident')
                        ->get()
                        ->mapWithKeys(function ($item) {
                            return [$item->modelref_incidenttype->cases => $item->count];
                        });
    
        return response()->json([
            'incidentCountsForPie' => $incidentCountsForPie
        ]);
    }
    
}
