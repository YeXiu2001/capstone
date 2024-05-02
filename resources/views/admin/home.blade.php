@extends('layouts.app')
<!-- Dependencies -->
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<!-- leaflet CSS -->
<link rel="stylesheet" href="{{url('assets/libs/leaflet/leaflet.css') }}"/>
<!-- leaflet locate control -->
<link rel="stylesheet" href="{{url('assets/js/maps/L.Control.Locate.min.css') }}" />

<!-- leaflet geocoder for search -->
<link rel="stylesheet" href="{{url('assets/js/maps/Control.Geocoder.css') }}" />       

<link rel="stylesheet" href="{{url('assets/libs/toastr/build/toastr.min.css')}}">
<script src="{{ url('assets/libs/dragula/dragula.min.js') }}"></script>

<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

@section('content')
<style>
    #dashboard_map {
         height: 39vh;
         }
     .pieCard {
        height: 50vh;
    } 

    #map-card{
        height: 50vh;
    }
</style>
<div class="row">
    <div class="col-xl-7">
        <div class="card" id="map-card">
            <div class="card-body">
               
                <h4 class="card-title mb-3">Map Cluster</h4>

                <div class="row">
                            <div id="dashboard_map"></div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-5">
            <div class="card pieCard">
                <div class="card-body">
                <div class="float-end">
                @php
                $yearspie = App\Models\incident_reports::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year');
                @endphp

                <select class="form-select form-select-sm" id="year-selector-pie" name="year-selector-pie">
                    @foreach($yearspie as $yearpie)
                        <option value="{{ $yearpie }}" {{ $yearpie == now()->year ? 'selected' : '' }}>{{ $yearpie }}</option>
                    @endforeach
                </select>
            </div>

                <h4 class="card-title mb-3">Incident Distibution</h4>
                        <div id="pie-chart"></div>
                </div>
            </div>
    </div>

             
    <div class="row">
        <div class="col">

            <div class="card">
                <div class="card-body">
                <div class="float-end">
                @php
                // Fetch the distinct years from your incidents data
                $years = App\Models\incident_reports::selectRaw('YEAR(created_at) as year')->distinct()->pluck('year');
                @endphp
                <select class="form-select form-select-sm ms-2" id="year-selector" name="year-selector">
                    @foreach($years as $year)
                    <option value="{{ $year }}" {{ $year == now()->year ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                </div>
                    <h4 class="card-title mb-3">Incident Distribution Graph</h4>
                        <div id="line-graph"></div>
                </div>
            </div>

        </div>
    </div>


    </div>
<!-- Map Implementation -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
    //declare layers
    var standard = L.tileLayer.provider('OpenStreetMap.Mapnik');
    var sat = L.tileLayer.provider('Esri.WorldImagery');


    const myGeoapify = "9e0570cdff3b4149a9752d093a45f38b";

    //basemaps
    var basemaps = {
        'Standard Map': standard,
        'Satellite Image': sat,

    }
    //Main map
    map = L.map('dashboard_map', {
        center:[8.241137015981792, 124.24375643514865], //lat, long
        zoom: 12,
        layers: [standard]
    });
    //Iligan City boundary geoJSON wikidata:Q285488 export geoJSON on overpass turbo, finalize on geojson.io
    var geoOptions = {
        maxZoom: 16,
        tolerance: 0,
        debug: 0,
        style:{
            color: "#0000FF",
        },
    };
    //OSM wikidata Q285488
    var ic_admin = L.geoJson(lineJSON, geoOptions).addTo(map);

    //shapefile
    var barangays = L.geoJson(ic_full_admin, geoOptions).addTo(map);

    //declare overlays
    //initialize overlays
    var overlays = {
        "Iligan Admin Boundary": ic_admin,
        "Barangays": barangays,
        // "labels": labels,
        // 'streets': streets
    }

    //map layers/ control layer of basemaps and overlays
    var maplayers = L.control.layers(basemaps,overlays).addTo(map);
    //search Control plugin https://github.com/perliedman/leaflet-control-geocoder
    L.Control.geocoder().addTo(map);
    //leaflet-locate plugin https://github.com/domoritz/leaflet-locatecontrol
    L.control.locate().addTo(map);

        });
</script>
<!-- Map Implementation -->

<script>
      document.addEventListener('DOMContentLoaded', function(){
        initializeEventListeners();  // Call a function to setup event listeners
         // Fetch initial data for the current year for both charts
        var currentYear = new Date().getFullYear().toString();
        fetchReports(currentYear);
        fetchPieChartData(currentYear);


        function initializeEventListeners() {
            var pieYearSelector = document.getElementById('year-selector-pie');
            var lineYearSelector = document.getElementById('year-selector');

            // Ensuring no multiple event bindings
            pieYearSelector.removeEventListener('change', handlePieYearChange);
            lineYearSelector.removeEventListener('change', handleLineYearChange);

            pieYearSelector.addEventListener('change', handlePieYearChange);
            lineYearSelector.addEventListener('change', handleLineYearChange);
        }

        function handlePieYearChange() {
            var pieYear = this.value;
            console.log('Fetching pie chart data for year:', pieYear);
            fetchPieChartData(pieYear);
        }

        function handleLineYearChange() {
            var year = this.value;
            console.log('Fetching line graph data for year:', year);
            fetchReports(year);
        }

        /**  -------------- Rendering Map -------------------------- */
        var markers = {}; // Object to hold your markers
        function renderMap(incidents) {
            var markersCluster = L.markerClusterGroup(); // Initialize the marker cluster group

            incidents.forEach(incident => {
                var popupContent = `<strong>Reporter:</strong> ${incident.reporter}<br>
                                    <strong>Contact Number:</strong> ${incident.contact}<br>
                                    <strong>Case:</strong> ${incident.case_type}<br>
                                    <strong>Description:</strong> ${incident.eventdesc}<br>
                                    ID: ${incident.id}`;

                if (incident.image_url) {
                    popupContent += `<br><img src="${incident.image_url}" style="max-width: 125px;">`;
                }

                // Determining the icon based on the incident type
                var iconUrl = '/markerIcons/default.png'; // Default icon
                var incidentType = incident.case_type.toLowerCase(); // Case insensitive comparison

                if (incidentType.includes('mountain search')) {
                    iconUrl = '/markerIcons/mountain-marker.png';
                } else if (incidentType.includes('water search')) {
                    iconUrl = '/markerIcons/water-marker.png';
                } else if (incidentType.includes('trauma case')) {
                    iconUrl = '/markerIcons/trauma-marker.png';
                } else if (incidentType.includes('medical case')) {
                    iconUrl = '/markerIcons/medical-marker.png';
                }

                // Creating a Leaflet Icon
                var customIcon = L.icon({
                    iconUrl: iconUrl,
                    iconSize: [75, 75], // Increase the size of the icon
                    iconAnchor: [37, 41], // Point of the icon which will correspond to marker's location
                    popupAnchor: [1, -34] // Point from which the popup should open relative to the iconAnchor
                });

                const marker = L.marker([parseFloat(incident.lat), parseFloat(incident.long)], {icon: customIcon})
                                .bindPopup(popupContent);
                markersCluster.addLayer(marker); // Add marker to the MarkerClusterGroup
            });

            map.addLayer(markersCluster); // Add the MarkerClusterGroup to the map
        }
         /**  -------------- ./Rendering Map -------------------------- */

        /** ----------------- Fetch to render line graphs ---------- */
        function fetchReports(year) {
            var headers = new Headers();
            headers.append('pragma', 'no-cache');
            headers.append('cache-control', 'no-cache');

            // Fetch data from the server
            fetch(`/dashboardMap?year=${year}`, { headers: headers })
                .then(response => response.json())
                .then(data => {
                    // Clear existing charts
                    clearChart("#line-graph");

                    // Render the charts with new data
                    renderMap(data.incidents);
                    renderLineGraph(data.monthlyIncidentCounts);
                })
                .catch(error => {
                    console.log('Error fetching incidents:', error);
                });
        }
        /** ----------------- Fetch to render line graphs ---------- */

        /** ----------------- Fetch to render pie chart ---------- */
        function fetchPieChartData(pieYear) {
            console.log('Initiating fetch for Pie Chart Data...');
            var headers = new Headers();
            headers.append('Pragma', 'no-cache');
            headers.append('Cache-Control', 'no-cache');

            fetch(`/dashboardPieChart?pieYear=${pieYear}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Data received for Pie Chart:', data);
                    clearChart("#pie-chart");
                    renderDonutChart(data.incidentCountsForPie);
                })
                .catch(error => {
                    console.error('Error fetching pie chart data:', error);
                });
        }
        /** ----------------- Fetch to render pie chart ---------- */   

 /** ------------------------ Pie Chart ------------------------- */
        function renderDonutChart(incidentCountsForPie) {
        var options = {
            series: Object.values(incidentCountsForPie),
            chart: {
                type: 'pie',
                height: '100%'
            },
                labels: Object.keys(incidentCountsForPie),
                responsive: [{
            
                options: {
                    legend: {
                        position: 'bottom'
                    },
                }
            }],
            legend: {
                position: 'bottom',
                offsetY: 0
              }   
        };

        var chart = new ApexCharts(document.querySelector("#pie-chart"), options);
        chart.render();
    }
 /** ------------------------ ./Pie Chart ------------------------- */

    //   -------------------Render Line Graph START -----------------------------
    function renderLineGraph(monthlyIncidentCounts) {
        let series = [];
        let categories = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

        for (let [type, data] of Object.entries(monthlyIncidentCounts)) {
            let monthlyData = new Array(12).fill(0); // Create an array with 12 zeros for each month
            data.forEach(point => {
                monthlyData[point.month - 1] = point.count; // -1 because months are zero-indexed in the array
            });
            
            series.push({ name: type, data: monthlyData });
        }

        var options = {
            series: series,
            chart: {
                height: '350',
                type: 'line',
            },
            xaxis: {
                categories: categories
            },
            yaxis: {
                title: {
                    text: 'Incident Count'
                }
            },
            stroke: {
                width: 3
            },
        };

        var chart = new ApexCharts(document.querySelector("#line-graph"), options);
        chart.render();
    }
    /** --------------------- ./Render Line Graph ------------------------------ */

    
        // Clear chart function
        function clearChart(chartId) {
                var chartEl = document.querySelector(chartId);
                if (chartEl) {
                    chartEl.innerHTML = '';
                }
            }
    });//DOMContentLoaded

</script>

<!-- Dependencies -->
<!-- Bootstrap Toasts Js -->
<script src="{{url('assets/libs/toastr/build/toastr.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-timezone/0.5.33/moment-timezone-with-data-10-year-range.min.js"></script>
<script src="{{url('assets/js/maps/allBarangay.js')}}"></script>    
<script src="{{url('assets/js/maps/line.js')}}"></script>
<script src="{{url('assets/js/maps/Iligan_full_admin_boundaries.js')}}"></script>
<!-- Leaflet JS -->
<script src="{{url('assets/libs/leaflet/leaflet.js') }}"></script>
<!-- leaflet Providers -->
<script src="{{url('assets/js/maps/leaflet-providers.js') }}"></script>
<!-- leaflet control geocoder -->
<script src="{{url('assets/js/maps/Control-Geocoder.js') }}"></script>
<!-- leaflet locate control -->
<script src="{{url('assets/js/maps/L.Control.Locate.min.js') }}"></script>

<script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster-src.js"></script>
<script src="{{url('assets/libs/apexcharts/dist/apexcharts.js') }}"></script>
@endsection