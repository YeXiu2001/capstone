@extends('layouts.app')
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<!-- leaflet CSS -->
<link rel="stylesheet" href="{{url('assets/libs/leaflet/leaflet.css') }}"/>
<!-- leaflet locate control -->
<link rel="stylesheet" href="{{url('assets/js/maps/L.Control.Locate.min.css') }}" />

<!-- leaflet geocoder for search -->
<link rel="stylesheet" href="{{url('assets/js/maps/Control.Geocoder.css') }}" />

<!-- Routing Machine -->
<link rel="stylesheet" href="{{url('assets/js/maps/leaflet-routing/dist/leaflet-routing-machine.css') }}" />

@section('content')
<style>
    .map-container {
        position: relative;
       }

    #routing_map {
        height: 87vh;
    }

    #incident_dtls_btn {
        position: absolute;
            bottom: 2px;
            left: 15px;
            z-index: 1000;
    }

</style>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20">
    @if($team)
    <p>Team Name: {{ $team->team_name }}</p>
    <p>Team ID: {{ $team->id }}</p>
    @else
        <p>No team assigned.</p>
    @endif

    @forelse ($incidents as $incident)
    <div>
        <p>Incident ID: {{ $incident->id }}</p>
        <p>Reporter: {{ $incident->reporter }}</p>
        <p>Contact: {{ $incident->contact }}</p>
        <p>Address: {{ $incident->address }}</p>
        <p>Latitude: {{ $incident->lat }}</p>
        <p>Longitude: {{ $incident->long }}</p>
        <p>Case Type: {{ $incident->modelref_incidenttype->cases }}</p>
        <p>Description: {{ $incident->eventdesc }}</p>
        <p>Image Directory: {{ $incident->imagedir }}</p>
        <p>Status: {{ $incident->status }}</p>
        <p>Created At: {{ $incident->created_at }}</p>
    </div>
    @empty
        <p>No incidents assigned to your team.</p>
    @endforelse
        <div class="map-container">
            <div id="routing_map">
            </div>
        </div>


    </div>

    <button id="incident_dtls_btn" class="btn btn-primary btn-sm mt-2">View Incident Details</button>
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
    map = L.map('routing_map', {
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
<!-- ./Map Implementation -->

<!-- Routing Implementation-->
<script>
    document.addEventListener('DOMContentLoaded', function() {

        navigator.geolocation.getCurrentPosition(function(location) {
        const userLat = location.coords.latitude;
        const userLng = location.coords.longitude;

        if (@json($incidents).length > 0) {
            const firstIncident = @json($incidents)[0]; // Using the first incident as an example

            // Add routing to the map
            L.Routing.control({
                waypoints: [
                    L.latLng(userLat, userLng), // User's current location
                    L.latLng(firstIncident.lat, firstIncident.long) // First incident's location
                ],
                routeWhileDragging: true
            }).addTo(map);
        }
    }, function(err) {
        console.error(err);
        alert("Error getting your location. Please enable location services.");
    });
    });
</script>
<!-- ./Routing Implementation -->
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
<!-- Leaflet Routing JS -->
<script src="{{url('assets/js/maps/leaflet-routing/dist/leaflet-routing-machine.js')}}"></script>

@endsection