@extends('layouts.app')
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<!-- leaflet CSS -->
<link rel="stylesheet" href="{{url('assets/libs/leaflet/leaflet.css') }}"/>
<!-- leaflet locate control -->
<link rel="stylesheet" href="{{url('assets/js/maps/L.Control.Locate.min.css') }}" />

<!-- leaflet geocoder for search -->
<link rel="stylesheet" href="{{url('assets/js/maps/Control.Geocoder.css') }}" />       


<script src="{{ url('assets/libs/dragula/dragula.min.js') }}"></script>
@section('content')
<div class="mobile-menu-overlay"></div>
<div class="main-container">
<div class="pd-ltr-20">
<style>
    #admin_map {
         height: 60vh; 
         }
</style>

<div class="row mb-2">
    <div class="col-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">List of Response Teams</h4>
                @include('partials.reports_teamtbl', ['rteams' => $rteams])
            </div>
        </div>
    </div>
    <div class="col-8">
            <div id="admin_map"></div>
    </div>
</div>


<div class="row mt-2 kanban-boards">
    <div class="col-lg-4">
        <div class="card mb-5" style="border-color: gray; border-width: 2px; border-style: solid;">
            <div class="card-body">
                <h4 class="card-title mb-4">Pending Incidents</h4>
                    <div id="pending-kanban" >
                        <div id="pending-reports" class="pb-5 task-list">
                            <!-- forelse Here to fetch data -->
                            @forelse($kanbanIncidents as $index => $reports)
                                @if($reports->status == 'pending')
                                <div class="card task-box" id="pending-rep-{{$index + 1}}" data-report-id="{{$reports -> id}}" style="border-color: black; border-width: 1px; border-style: solid;">
                                    <div class="card-body pending-cardBody">
                                        <div class="dropdown float-end pending-dropdown">
                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item view-in-map" href="#" data-report-id="{{$reports->id}}">View in Map</a>
                                                    <!-- <a class="dropdown-item">View Complete Details</a> -->
                                                    <a class="dropdown-item edittask-details edit-preport-details" href="#" data-report-id="{{$reports->id}}" data-bs-toggle="modal" data-bs-target="edit_preport_modal ">Edit</a>
                                                    <a class="dropdown-item deletetask" href="#">Delete</a>
                                                </div>
                                            </div>
                                        <div class="float-end ms-2">
                                            <span class="badge rounded-pill badge-soft-secondary font-size-12" id="k-rep-status" >{{ $reports -> status }}</span>
                                        </div>
                                        <div>
                                            <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark" id="k-rep-type" >{{ $reports -> modelref_incidenttype->cases  }}</a></h5>
                                            <strong><p class="mb-1" id="k-report-reporter" >{{ $reports -> reporter }}</p></strong>
                                            <strong><p class="mb-1" id="k-report-contact" >{{ $reports -> contact }}</p></strong>
                                            <p class="text-muted mb-1" id="k-report-createdAt" >{{ $reports -> created_at->format('d-m-Y') }}</p>
                                            <p class="text-muted mb-4" id="k-report-address">{{ $reports->address }}</p>

                                        </div>
                                        <div class="text">
                                        <h5 class="font-size-15 mb-1" id="k-report-eventdesc">{{ $reports->eventdesc ?? 'No Description Provided' }}</h5>
                                    </div>
                                    <img src="{{ asset('images/' . $reports->imagedir) }}" alt="No Image Sent" style="width: 200px;">
                                </div>
                            </div>
                        <!-- end task card -->
                        @endif
                        @empty
                        @endforelse
                    </div>

                    <div class="text-center d-grid">
                        <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light addtask-btn" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg" data-id="#upcoming-task"><i class="mdi mdi-plus me-1"></i> Add New</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-lg-4">
        <div class="card mb-2" style="border-color: orange; border-width: 2px; border-style: solid;">
            <div class="card-body">
                <h4 class="card-title mb-4">In Progress</h4>
                    <div id="inprog-kanban">
                        <div id="ongoing-reports" class="pb-5 task-list">
                            <!-- forelse Here to fetch data -->
                            @forelse($kanbanIncidents as $index => $reports)
                                @if($reports->status == 'ongoing')
                            <div class="card task-box" id="inprog-rep-{{$index + 1}}" data-report-id='{{$reports->id}}' style="border-color: black; border-width: 1px; border-style: solid;">
                                    <div class="card-body ongoing-cardBody">
                                    <div class="dropdown float-end ongoing-dropdown">
                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                <a class="dropdown-item view-in-map" href="#" data-report-id="{{$reports->id}}">View in Map</a>
                                                    <a class="dropdown-item deletetask" href="#">Dismiss</a>
                                                </div>
                                            </div>
                                        <div class="float-end ms-2">
                                            <span class="badge rounded-pill badge-soft-warning font-size-12" id="k-rep-status" >{{ $reports->status }}</span>
                                        </div>
                                        <div>
                                            <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark" id="k-rep-type" >{{ $reports->modelref_incidenttype->cases }}</a></h5>


                                            <strong><p class="mb-1" id="k-report-reporter" >{{ $reports -> reporter }}</p></strong>
                                            <strong><p class="mb-1" id="k-report-contact" >{{ $reports -> contact }}</p></strong>
                                            <p class="text-muted mb-1" id="k-report-createdAt" >{{ $reports -> created_at->format('d-m-Y') }}</p>
                                            <p class="text-muted mb-4" id="k-report-address">{{ $reports->address }}</p>
                                        </div>
                                        <div class="text">
                                        <h5 class="font-size-15 mb-1" id="k-report-eventdesc">{{ $reports->eventdesc ?? 'No Description Provided' }}</h5>
                                    </div>
                                    <img src="{{ asset('images/' . $reports->imagedir) }}" alt="No Image Sent" style="width: 200px;">
                                     <!-- Display deployment information -->
                                    <!-- Accumulate team names -->
                                    @php
                                        $deployedTeams = $reports->deployments->pluck('deployedRteam.team_name')->unique();
                                        $deployedBy = optional($reports->deployments->first())->deployedBy->name ?? 'N/A';
                                    @endphp

                                    @if($deployedTeams->isNotEmpty())
                                        <p class="mb-1 mt-2" id="k-deployed-team"><strong>Deployed Teams:</strong> {{ implode(', ', $deployedTeams->toArray()) }}</p>
                                    @endif
                                    
                                    <!-- Display deployed by -->
                                    <p class="mb-1" id="k-deployedBy"><strong>Deployed By:</strong> {{ $deployedBy }}</p>
                                </div>

                                   
                            </div>
                        <!-- end task card -->
                        @endif
                        @empty
                        @endforelse
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-lg-4">
        <div class="card mb-5" style="border-color: green; border-width: 2px; border-style: solid;">
            <div class="card-body">
                <h4 class="card-title mb-4">Resolved</h4>
                    <div id="resolved-kanban">
                        <div id="resolved-reports" class="pb-5 task-list">
                           <!-- forelse Here to fetch data -->
                           @forelse($kanbanIncidents as $index => $reports)
                            @if($reports->status == 'resolved')
                            <div class="card task-box" id="resolved-rep-{{$index + 1}}" data-report-id='{{$reports->id}}' style="border-color: black; border-width: 1px; border-style: solid;">
                                <div class="card-body">
                                <div class="dropdown float-end resolved-dropdown">
                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item view-in-map" href="#" data-report-id="{{$reports->id}}">View in Map</a>
                                                    <a class="dropdown-item">View Complete Details</a>
                                                </div>
                                            </div>
                                    <div class="float-end ms-2">
                                        <span class="badge rounded-pill badge-soft-success font-size-12" id="k-rep-status" >{{ $reports->status }}</span>
                                    </div>
                                    <div>
                                        <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark" id="k-rep-type" >{{ $reports->modelref_incidenttype->cases }}</a></h5>
                                        <strong><p class="mb-1" id="k-report-reporter" >{{ $reports -> reporter }}</p></strong>
                                        <strong><p class="mb-1" id="k-report-contact" >{{ $reports -> contact }}</p></strong>
                                        <p class="text-muted mb-1" id="k-report-createdAt" >{{ $reports -> created_at->format('d-m-Y') }}</p>
                                        <p class="text-muted mb-4" id="k-report-address">{{ $reports->address }}</p>
                                    </div>
                                    <div class="text">
                                        <h5 class="font-size-15 mb-1" id="k-report-eventdesc">{{ $reports->eventdesc ?? 'No Description Provided' }}</h5>
                                    </div>
                                    <img src="{{ asset('images/' . $reports->imagedir) }}" alt="No Image Sent" style="width: 200px;">
                                    <!-- Display deployment information -->
                                    <!-- Accumulate team names -->
                                    @php
                                        $deployedTeams = $reports->deployments->pluck('deployedRteam.team_name')->unique();
                                        $deployedBy = optional($reports->deployments->first())->deployedBy->name ?? 'N/A';
                                    @endphp

                                    @if($deployedTeams->isNotEmpty())
                                        <p class="mb-1 mt-2" id="k-deployed-team"><strong>Deployed Teams:</strong> {{ implode(', ', $deployedTeams->toArray()) }}</p>
                                    @endif
                                    
                                    <!-- Display deployed by -->
                                    <p class="mb-1" id="k-deployedBy"><strong>Deployed By:</strong> {{ $deployedBy }}</p>
                                    </div>
                            </div>
                            @endif
                            @empty
                            @endforelse
                        <!-- end task card -->
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <!-- end col -->

</div>
</div>


<!-- Pending Edit Modal -->
<div class="modal fade" id="edit_preport_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Incident Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit_preport_form">
                    @csrf
                    <input type="hidden" id="edit-preport-report-id" name="reportId">

                    <div class="mb-4">
                        <div class="mb-2">
                            <label class="form-label" for="edit-preport-name">Name</label>
                            <input type="text" id="edit-preport-name" name="edit-preport-name" class="form-control" disabled />
                        </div>
                        
                        <div class="mb-2">
                            <label class="form-label" for="edit-preport-contact">Contact Number</label>
                            <input type="text" id="edit-preport-contact" name="edit-preport-contact" class="form-control" disabled />
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="edit-preport-address">Address</label>
                            <input type="text" id="edit-preport-address" name="edit-preport-address" class="form-control"/>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="edit-preport-incident">Incident Type</label>
                            <select class="form-control select2 col-10" id="edit-preport-incident" name="edit-preport-incident" required style="width: 100%;">
                            <option value="" selected hidden>Select Incident</option>
                            @foreach($incident_types as $incident)
                            <option value="{{$incident->id}}">{{$incident->cases}}</option>
                            @endforeach
                            </select>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="edit-preport-eventdesc">Description</label>
                            <textarea class="form-control" id="edit-preport-eventdesc" name="edit-preport-eventdesc" rows="4"></textarea>
                        </div>
                    </div>

                   <img id="edit-preport-image" alt="No image provided" style="width: 300px;">


            </div><!-- modal body end -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit-preport-submitBtn" class="btn btn-primary">Submit</button>
                </div>

                </form>
        </div>
    </div>
</div>
<!-- Pending Edit Modal -->

<!-- Select Response Team Modal -->
<div class="modal fade" id="select_rteam_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Select Response Teams</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="select_rteam_form">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label" for="edit-preport-name">Available Response Teams</label>
                        <select class="form-control" id="assign-rteam" name="assign-rteam[]"  multiple="multiple" required style="width: 100%;">
                            <option value="" hidden disabled>Select Members</option>
                            @forelse($available_RTeams as $rt)
                            <option value="{{$rt->id}}">{{$rt->team_name}}</option>
                            @empty
                            <option value="">No Response Teams Available</option>
                            @endforelse
                            </select>
                    </div>  
                </div><!-- modal body end -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="assign-rteam-submitBtn" class="btn btn-primary">Submit</button>
                </div>
                </form>
        </div>
    </div>
</div>
<!-- Select Response Team Modal End -->

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
    map = L.map('admin_map', {
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




<!-- Kanban script -->
<script>
    $(document).ready(function() {
            /** ----------------- Fetch Table -------------------------- */
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();

        let page = $(this).attr('href').split('page=')[1];
        fetchTeamTbl(page);
    });

    function fetchTeamTbl(page) {
        $.ajax({
            url: '/reports-teamtbl?page=' + page,
            success: function(data) {
                $('#reports_teamtbl_container').html(data);
            }
    });
    }
    /** ----------------- Fetch Table -------------------------- */

    // maps Event Start
    var markers = {}; // Object to hold your markers

    // Fetch and map incidents to markers upon DOM ready
    fetchIncidentsAndMapMarkers();

    // Handle dynamic "View in Map" clicks with event delegation
    $('.kanban-boards').on('click', '.view-in-map', function() {
        var reportId = $(this).data('report-id').toString(); // Convert to string for key comparison

        if (markers[reportId]) {
            var marker = markers[reportId];
            map.flyTo(marker.getLatLng(), 15, { animate: true });
            marker.openPopup();
        } else {
            console.log('Marker not found for report ID:', reportId);
        }
    });
    // Maps Event END

    // Dragula Initialization and handle invalid moves
    dragula([document.getElementById("pending-reports"), document.getElementById("ongoing-reports"), document.getElementById("resolved-reports")])
        .on('drag', function(el, source) {
            // Store the source container ID to check during the drop
            el.dataset.sourceId = source.id;
            // Optionally, you can also store the next sibling of the dragged element for reference if the move is invalid
            el.dataset.nextSiblingId = el.nextElementSibling ? el.nextElementSibling.getAttribute('id') : null;
        })
        .on('drop', function(el, target, source, sibling) {
            var reportId = el.getAttribute('data-report-id');
            var sourceId = el.dataset.sourceId; // Retrieve the source container ID
            var targetId = target.id;
            var isValidMove = checkValidMove(sourceId, targetId);
            var nextSiblingId = el.dataset.nextSiblingId ? document.getElementById(el.dataset.nextSiblingId) : null; // Get the stored next sibling

            if (!isValidMove) {
                swal.fire({
                    icon: 'error',
                    title: 'Invalid Move',
                    text: 'Reports can only move one step forward in the Kanban board.'
                });

                // Check if there was a next sibling and return the report card to its original position
                if (nextSiblingId) {
                    source.insertBefore(el, nextSiblingId);
                } else {
                    // If there was no next sibling, the item was the last in the list, so just append it to the source container
                    source.appendChild(el);
                }
                return;
            }

                if (targetId === 'ongoing-reports') {
                    $('#select_rteam_modal').data('report-id', reportId).modal('show');
                    // Handle logic for moving to 'Ongoing'
                    handleMoveToOngoing(reportId);
                } else if (targetId === 'resolved-reports') {
                    // Handle logic for moving to 'Resolved'
                    handleMoveToResolved(reportId);
                }
                // No action needed for invalid moves as they're handled above
            });
        // Dragula Initialization and handle invalid moves END


    function checkValidMove(sourceId, targetId) {
        // Define valid transitions
        const transitions = {
            'pending-reports': 'ongoing-reports',
            'ongoing-reports': 'resolved-reports'
        };

        // Check if the move is valid based on the defined transitions
        return transitions[sourceId] === targetId;
    }

    function handleMoveToOngoing(reportId) {
        // Logic for handling move to 'Ongoing', already implemented in your script
        console.log(`Preparing to move report ${reportId} to Ongoing.`);

        // Handle when a report is moved to 'Ongoing'
    $('#assign-rteam').select2({ dropdownParent: $('#select_rteam_modal') });

    // Handle the report status update and team deployment
    $('#select_rteam_form').submit(function(e) {
        e.preventDefault();
        var reportId = $('#select_rteam_modal').data('report-id');
        var selectedRteams = $('#assign-rteam').val();

        $.ajax({
            url: '/kanban-report-deploy',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                reportId: reportId,
                newStatus: 'ongoing',
                deployedRteam: selectedRteams
            },
            success: function(response) {
                console.log('Report and deployment updated successfully.');

            // Select the report card based on its current ID
            var reportCard = $(`#pending-rep-${reportId}`).detach();
            
            // Update the report card's ID to reflect its new 'Ongoing' status
            reportCard.attr('id', `inprog-rep-${reportId}`);

            // Remove any existing dropdown
            reportCard.find('.dropdown').remove();

            // Append new dropdown specific for 'ongoing'
            var ongoingDropdownHtml = `
                <div class="dropdown float-end ongoing-dropdown">
                    <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item view-in-map" href="#" data-report-id="${reportId}">View in Map</a>
                        <a class="dropdown-item deletetask" href="#">Dismiss</a>
                    </div>
                </div>
            `;
            reportCard.find('.card-body').prepend(ongoingDropdownHtml);
                // Update the status badge
                reportCard.find('.badge').removeClass('badge-soft-secondary').addClass('badge-soft-warning').text('Ongoing');
                if (reportCard.find("#k-deployed-team").length) {
                    reportCard.find("#k-deployed-team").html(`<strong>Deployed Teams:</strong> ${response.deployedTeams.join(', ')}`);
                } else {
                    // If not, append them
                    var deployedTeamsHtml = `<p class="mb-1 mt-2" id="k-deployed-team"><strong>Deployed Teams:</strong> ${response.deployedTeams.join(', ')}</p>`;
                    reportCard.find('.card-body').append(deployedTeamsHtml);
                }

                if (reportCard.find("#k-deployedBy").length) {
                    reportCard.find("#k-deployedBy").html(`<strong>Deployed By:</strong> ${response.deployedBy}`);
                } else {
                    var deployedByHtml = `<p class="mb-1" id="k-deployedBy"><strong>Deployed By:</strong> ${response.deployedBy}</p>`;
                    reportCard.find('.card-body').append(deployedByHtml);
                }
                
                // Append to ongoing reports
                $('#ongoing-reports').append(reportCard);

                // Reinitialize select2 for the response teams
                fetchAvailableTeams();

                // Optionally, update the markers if the incident location/marker details could change
                fetchIncidentsAndMapMarkers();
                fetchTeamTbl();
                // Close the modal
                $('#select_rteam_modal').modal('hide');
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    });
    // Handle the Pending to ongoin and team deployment END
    }
    
    // handle ongoing to resolved
    function handleMoveToResolved(reportId) {
        console.log(`Preparing to move report ${reportId} to Resolved.`);

        // Now correctly identifying the report card
        var reportCard = $(`#inprog-rep-${reportId}`);
        if (!reportCard.length) {
            console.error('Report card not found:', reportId);
            return; // Exit if the report card cannot be found
        }

        $.ajax({
            url: '/kanban-report-resolve',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                reportId: reportId,
                newStatus: 'resolved'
            },
            success: function(response) {
                console.log('Report status updated to resolved.');
                // Since the report card is already selected, no need to re-select or detach it

                // Remove any existing dropdown
                reportCard.find('.dropdown').remove();

                // Append the 'Resolved' specific dropdown
                var resolvedDropdownHtml = `
                    <div class="dropdown float-end resolved-dropdown">
                        <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item view-in-map" href="#" data-report-id="${reportId}">View in Map</a>
                            <a class="dropdown-item">View Complete Details</a>
                        </div>
                    </div>
                `;
                reportCard.find('.card-body').prepend(resolvedDropdownHtml);

                // Update the status badge to 'Resolved'
                reportCard.find('.badge').removeClass('badge-soft-warning').addClass('badge-soft-success').text('Resolved');

                // Move the updated report card to the "resolved-reports" column
                $('#resolved-reports').append(reportCard);

                // Optionally, update the markers if the incident location/marker details could change
                fetchIncidentsAndMapMarkers();
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }


    // Handle the move to 'Resolved' status
    
    
    function fetchAvailableTeams() {
        $.ajax({
            url: '/get-available-teams',
            success: function(response) {
                var $select = $('#assign-rteam');
                $select.empty(); // Clear current options
                response.available_RTeams.forEach(team => {
                    var newOption = new Option(team.team_name, team.id, false, false);
                    $select.append(newOption).trigger('change');
                });
                // Reinitialize select2
                $select.select2({ dropdownParent: $('#select_rteam_modal') });
            }
        });
    }

    function fetchIncidentsAndMapMarkers() {
        fetch('/fetch-incidents-map')
        .then(response => response.json())
        .then(data => {
            data.forEach(incident => {
                var popupContent = `<strong>Reporter:</strong> ${incident.reporter}<br>
                                        <strong>Contact Number:</strong> ${incident.contact}<br>
                                        <strong>Case:</strong> ${incident.case_type}<br>
                                        <strong>Description:</strong> ${incident.eventdesc}
                                        ID: ${incident.id}`;

                if (incident.image_url) {
                    popupContent += `<br><img src="${incident.image_url}" style="max-width: 125px;">`; // Adjust styling as needed
                }

                const marker = L.marker([parseFloat(incident.lat), parseFloat(incident.long)])
                                .addTo(map)
                                .bindPopup(popupContent);

                markers[incident.id.toString()] = marker; // Ensure key is a string
            });
        })
        .catch(error => console.log('Error fetching incidents:', error));
    }
    });

</script>
<!-- kanban end -->

<!-- START Edit Pending Report Modal -->
<script>
    $(document).ready(function() {
        $('#edit-preport-incident').select2({
                dropdownParent: $('#edit_preport_modal')
            });
        $('.edit-preport-details').click(function() {
            $('#edit_preport_modal').modal('toggle');
        });

        $('.edit-preport-details').on('click', function() {
            var reportId = $(this).data('report-id');
            console.log('Report ID on edit click:', reportId); // Debugging line
            $.ajax({
                url: '/get-preport-details/' + reportId,
                type: 'GET',
                success: function(data) {
                    // Assuming 'data' contains the report details
                    $('#edit-preport-report-id').val(reportId);
                    $('#edit-preport-name').val(data.reporter);
                    $('#edit-preport-contact').val(data.contact);
                    $('#edit-preport-address').val(data.address);
                    $('#edit-preport-eventdesc').val(data.eventdesc);
                    
                    // Set the Select2 dropdown value and trigger change for Select2 to update
                    $('#edit-preport-incident').val(data.incident).trigger('change');
                // Ensure the image path is correct. Add a leading slash if necessary.
                    var imagePath = '/images/' + data.imagedir; // Adjust according to your actual images directory
                    $('#edit-preport-image').attr('src', imagePath);

                    // Store the report ID for the update action
                    $('#edit-preport-modal').data('report-id', reportId);

                    console.log('Fetched report ID:', reportId);
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }),
    
        $('#edit_preport_form').on('submit', function(e) {
        e.preventDefault(); // Prevent the default form submission
        var reportId = $('#edit-preport-report-id').val();


        var formData = {
            // Since CSRF token is included in the form, grab it from there
            _token: $('input[name="_token"]').val(),
            address: $('#edit-preport-address').val(),
            eventdesc: $('#edit-preport-eventdesc').val(),
            incident: $('#edit-preport-incident').val()
        };
        console.log('Updating report:', formData, 'ID:', reportId);
        $.ajax({
            url: '/update-preport-details/' + reportId,
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log('Report updated successfully');
                // Close the modal
                 // Assuming your report cards have IDs structured like "pending-rep-{reportId}"
                  var reportCardSelector = '#pending-rep-' + reportId;

                // Update the Kanban card with new values
                $(reportCardSelector).find('#k-report-address').text(formData.address);
                $(reportCardSelector).find('#k-report-eventdesc').text(formData.eventdesc);

                // Update the incident type displayed on the card. This requires fetching the text of the selected option.
                var incidentTypeName = $('#edit-preport-incident option:selected').text();
                $(reportCardSelector).find('#k-rep-type').text(incidentTypeName);

                // Close the modal
                $('#edit_preport_modal').modal('hide');
                $('#edit_preport_form').trigger('reset');

                swal.fire({
                    icon: 'success',
                    title: 'Report updated successfully',
                    showConfirmButton: false,
                    timer: 1500
                });
            },
            error: function(error) {
                console.error('Error updating report:', error);
                // Handle error
            }
        });
    });
    });
</script>
<!-- Edit Pending Report Modal END -->

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
@endsection