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
@vite(['resources/js/app.js'])
@section('content')
<style>
    .map-container {
        position: relative;
    }

    #routing_map {
        height: 80vh;
    }

    .button-container{
        position: absolute;
        bottom: 2px;
        z-index: 1000;
        left: 2px
    }
</style>

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20">
    @if($team)
        @if($incidents->isEmpty())
            <p>No incidents assigned to your team.</p>
        @else
            <div class="map-container">
                <div id="routing_map"></div>
                <div class="button-container mt-2">
                    <button type="button" class="btn btn-primary btn-sm mt-2" id="incident_dtls_btn" data-bs-toggle="modal" data-bs-target="#incident-details-modal"><i class="bx bx-detail"></i> Details</button>
                    <button type="button" class="btn btn-primary btn-sm mt-2" id="btn-resolve-report" data-report-id="{{ $incidents->first()->id }}"><i class="bx bx-check"></i> Resolve Report</button>
                </div>
            </div>
        @endif
    @else
        <p>No team assigned.</p>
    @endif
    
    </div>
<div class="modal fade" id="incident-details-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Incident Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                @forelse ($incidents as $incident)
                <div>
                    <p hidden>Incident ID: {{ $incident->id }}</p>
                    <p>Reporter: {{ $incident->reporter }}</p>
                    <p>Contact: {{ $incident->contact }}</p>
                    <p>Address: {{ $incident->address }}</p>
                    <p>Case Type: {{ $incident->modelref_incidenttype->cases }}</p>
                    <p>Description: {{ $incident->eventdesc ?? 'No Description Provided' }}</p>
                    <img src="{{ asset('images/' . $incident->imagedir) }}" alt="No Image Sent" width="300vh">
                </div>
                @empty
                    <p>No incidents assigned to your team.</p>
                @endforelse
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
        const noTeam = @json($noTeam);

        if (noTeam) {
            Swal.fire({
                icon: 'warning',
                title: 'No Team',
                text: 'You are not assigned to any team.',
                showConfirmButton: true,
            });

            return; // Exit early
        }

        const incidents = @json($incidents);
        if (incidents.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No Reports',
                text: 'You have no reports assigned to your team yet.',
                showConfirmButton: true,
            });

            return; // Exit early
        }

        navigator.geolocation.getCurrentPosition(function(location) {
            const userLat = location.coords.latitude;
            const userLng = location.coords.longitude;

            if (@json($incidents).length > 0) {
                const firstIncident = @json($incidents)[0]; // Using the first incident as an example

                // Initialize the router with options for the OSRM backend
                var router = L.Routing.osrmv1({
                    serviceUrl: 'https://router.project-osrm.org/route/v1',
                    profile: 'car', // Specify the mode of transport
                    routingOptions: {
                        alternatives: false, // Set to false if you want to receive only the shortest route
                    }
                });

                // Then, pass the router when creating the Routing control
                var routingControl = L.Routing.control({
                    waypoints: [
                        L.latLng(userLat, userLng), // User's current location
                        L.latLng(firstIncident.lat, firstIncident.long) // First incident's location
                    ],
                    router: router,
                    routeWhileDragging: false,
                    draggableWaypoints: true,
                }).addTo(map);
            }
        }, function(err) {
            console.error(err);
            alert("Error getting your location. Please enable location services.");
        });
    });
</script>
<!-- ./Routing Implementation -->

<!-- Resolve Report and Team to Available -->
<script>
    document.addEventListener('DOMContentLoaded', function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#btn-resolve-report').on('click', function(e) {
            e.preventDefault(); 

            var reportId = $(this).data('report-id');

            // Perform the AJAX request
            $.ajax({
                url: '/report-resolve/' + reportId, // The URL to your route
                type: 'POST', // The HTTP method
                data: {
                    id: reportId // Send the report ID along with the request
                },
                success: function(response) {
                    console.log('Report Updated Successfully')
                    console.log('Team is now Available')

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success,
                        showConfirmButton: true,
                    });
                    $('#rep-status').text('Resolved');
                    $('#team-status').text('Available');
                },
                error: function(xhr) {
                    // Handle any errors
                    alert('Error: ' + xhr.responseText); // Alert the error message
                }
            });
        });
    });
</script>
<!-- Resolve Report and Team to Available -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const teamId = @json(auth()->user()->rtMembers->first()->teamRefTeams()->value('id'));

        Echo.channel('teams.' + teamId)
            .listen('IncidentDeployed', (e) => {
                console.log('New Incident received', e); // Debugging line
                Swal.fire({
                    icon: 'info',
                    title: 'New Incident Deployed',
                    html: `
                        <p><strong>Reporter:</strong> ${e.incident_reporter}</p>
                        <p><strong>Address:</strong> ${e.incident_address}</p>
                        <p><strong>Description:</strong> ${e.incident_description}</p>
                    `,
                    showConfirmButton: true,
                    confirmButtonText: 'Acknowledge'
                }).then((result) => {
                    if (result.isConfirmed) {
                        location.reload(); // Reload the page
                    }
                });
            });
    });
</script>


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
<script src="{{url('assets/js/maps/leaflet-routing/dist/leaflet-routing-machine.min.js')}}"></script>

@endsection
