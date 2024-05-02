@extends('layouts.app')
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
<div class="mobile-menu-overlay"></div>
<div class="main-container">
<div class="pd-ltr-20">
<style>
    #admin_map {
         height: 50vh; 
         }
    
    .top-card{
        height: 50vh;
    }

    #reports_team_tbl {
    font-size: 12px; /* Adjust to a suitable smaller size */
    width: 100%; /* Ensures the table stretches to fit its container */
}

</style>

<div class="row mb-2">
    <div class="col-5">
        <div class="card top-card">
            <div class="card-body">
                <h4 class="card-title mb-4">List of Response Teams</h4>
                @include('partials.reports_teamtbl', ['rteams' => $rteams])
            </div>
        </div>
    </div>
    <div class="col-7">
            <div id="admin_map"></div>
    </div>
</div>

<div class=" row card">
    <div class="mt-3">
        <h4 class="card-title float-start">Handle Reports</h4>
        <button class="ms-2 btn btn-primary btn-sm mb-2 float-end" id="refetch-data">Refetch Data</button>
        <a href="/allreports" class="btn btn-primary btn-sm mb-2 float-end">View All Reports</a>
    </div>
            <div class="card-body">
                <div class="row kanban-boards">
                    <div class="col-lg-4">
                        <div class="card mb-5" style="border-color: gray; border-width: 2px; border-style: solid;">
                            <div class="card-body">
                               
                                <h4 class="card-title mb-4">Pending Incidents</h4>
                                    <div id="pending-kanban" >
                                        <div id="pending-reports" class="pb-5 task-list">
                                            <!-- forelse Here to fetch data -->

                                                <div class="card task-box" id="pending-rep-" data-report-id="" style="border-color: black; border-width: 1px; border-style: solid;">
                                                    <div class="card-body pending-cardBody">
                                                        <div class="dropdown float-end pending-dropdown">
                                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item view-in-map" href="#" data-report-id="">View in Map</a>
                                                                    <!-- <a class="dropdown-item">View Complete Details</a> -->
                                                                    <a class="dropdown-item edittask-details edit-preport-details" href="#" data-report-id="" data-bs-toggle="modal" data-bs-target="edit_preport_modal ">Edit</a>
                                                                    <a class="dropdown-item dismiss-rep" data-report-id="">Dismiss Report</a>
                                                                </div>
                                                            </div>
                                                        <div class="float-end ms-2">
                                                            <span class="badge rounded-pill badge-soft-secondary font-size-12" id="k-rep-status" ></span>
                                                        </div>
                                                        <div>
                                                            <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark" id="k-rep-type" ></a></h5>
                                                            <strong><p class="mb-1" id="k-report-reporter" ></p></strong>
                                                            <strong><p class="mb-1" id="k-report-contact" ></p></strong>
                                                            <p class="text-muted mb-1" id="k-report-createdAt" ></p>
                                                            <p class="text-muted mb-4" id="k-report-address"></p>

                                                        </div>
                                                        <div class="text">
                                                        <h5 class="font-size-15 mb-1" id="k-report-eventdesc"></h5>
                                                    </div>
                                                    <img src="{{ asset('images') }}" alt="No Image Sent" style="width: 200px;">
                                                </div>
                                            </div>
                                        <!-- end task card -->
                                    </div>

                                    <!-- <div class="text-center d-grid">
                                        <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light addtask-btn" data-bs-toggle="modal" data-bs-target=".bs-example-modal-lg" data-id="#upcoming-task"><i class="mdi mdi-plus me-1"></i> Add New</a>
                                    </div> -->
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
                                          
                                            <div class="card task-box" id="inprog-rep" data-report-id='' style="border-color: black; border-width: 1px; border-style: solid;">
                                                    <div class="card-body ongoing-cardBody">
                                                    <div class="dropdown float-end ongoing-dropdown">
                                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                <a class="dropdown-item view-in-map" href="#" data-report-id="">View in Map</a>
                                                                </div>
                                                            </div>
                                                        <div class="float-end ms-2">
                                                            <span class="badge rounded-pill badge-soft-warning font-size-12" id="k-rep-status" ></span>
                                                        </div>
                                                        <div>
                                                            <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark" id="k-rep-type" ></a></h5>


                                                            <strong><p class="mb-1" id="k-report-reporter" ></p></strong>
                                                            <strong><p class="mb-1" id="k-report-contact" ></p></strong>
                                                            <p class="text-muted mb-1" id="k-report-createdAt" ></p>
                                                            <p class="text-muted mb-4" id="k-report-address"></p>
                                                        </div>
                                                        <div class="text">
                                                        <h5 class="font-size-15 mb-1" id="k-report-eventdesc"></h5>
                                                    </div>
                                                    <img src="{{ asset('images/') }}" alt="No Image Sent" style="width: 200px;">
                                                    <!-- Display deployment information -->


                                                        <p class="mb-1 mt-2" id="k-deployed-team"><strong>Deployed Teams:</strong></p>
                                                    
                                                    <!-- Display deployed by -->
                                                    <p class="mb-1" id="k-deployedBy"><strong>Deployed By:</strong></p>
                                                </div>

                                                
                                            </div>
                                        <!-- end task card -->
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
                                            <div class="card task-box" id="resolved-rep-" data-report-id='' style="border-color: black; border-width: 1px; border-style: solid;">
                                                <div class="card-body">
                                                <div class="dropdown float-end resolved-dropdown">
                                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                                    <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <a class="dropdown-item view-in-map" href="#" data-report-id="">View in Map</a>
                                                                    <a class="dropdown-item">View Complete Details</a>
                                                                </div>
                                                            </div>
                                                    <div class="float-end ms-2">
                                                        <span class="badge rounded-pill badge-soft-success font-size-12" id="k-rep-status" ></span>
                                                    </div>
                                                    <div>
                                                        <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark" id="k-rep-type" ></a></h5>
                                                        <strong><p class="mb-1" id="k-report-reporter" ></p></strong>
                                                        <strong><p class="mb-1" id="k-report-contact" ></p></strong>
                                                        <p class="text-muted mb-1" id="k-report-createdAt" ></p>
                                                        <p class="text-muted mb-4" id="k-report-address"></p>
                                                    </div>
                                                    <div class="text">
                                                        <h5 class="font-size-15 mb-1" id="k-report-eventdesc"></h5>
                                                    </div>
                                                    <img src="" alt="No Image Sent" style="width: 200px;">
                                                    <!-- Display deployment information -->
                                                    <!-- Accumulate team names -->
                                                    <p class="mb-1 mt-2" id="k-deployed-team"><strong></strong></p>
                                                    <!-- Display deployed by -->
                                                    <p class="mb-1" id="k-deployedBy"><strong>Deployed By:</strong> </p>
                                                    </div>
                                            </div>
                                            
                                        <!-- end task card -->
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                    <!-- end col -->

                </div>
            </div>
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

<!-- New Kanban Script -->
<script>
    document.addEventListener('DOMContentLoaded', function(){
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

    /** ----------------- Fetch Kanban -------------------------- */
    function fetchKanbanData() {
        $.ajax({
            url: '/kanban-data',
            success: function(data) {
                renderKanban(data);
            },
            error: function(error) {
                console.error("Error fetching Kanban data:", error);
            }
        });
    }

    function renderKanban(reports) {
        $('#pending-reports').empty();
        $('#ongoing-reports').empty();
        $('#resolved-reports').empty();

        reports.forEach(report => {
            let deploymentInfoHtml = '';

            if (report.status === 'ongoing' || report.status === 'resolved') {
                const deployedTeams = report.deployments.teams.join(', ');
                const deployedBy = report.deployments.deployedBy;

                deploymentInfoHtml = `
                    <p class="mb-1 mt-2" id="k-deployed-team"><strong>Deployed Teams:</strong> ${deployedTeams}</p>
                    <p class="mb-1" id="k-deployedBy"><strong>Deployed By:</strong> ${deployedBy}</p>
                `;
            }

            let reportHtml = `
                <div class="card task-box" id="${report.status}-rep-${report.id}" data-report-id="${report.id}" style="border-color: black; border-width: 1px; border-style: solid;">
                    <div class="card-body ${report.status}-cardBody">
                        <div class="dropdown float-end ${report.status}-dropdown">
                            <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item view-in-map" href="#" data-report-id="${report.id}">View in Map</a>
                                ${report.status === 'pending' ? `<a class="dropdown-item edittask-details edit-preport-details" href="#" data-report-id="${report.id}" data-bs-toggle="modal" data-bs-target="#edit_preport_modal">Edit</a>` : ''}
                                ${report.status === 'pending' ? `<a class="dropdown-item dismiss-rep" data-report-id="${report.id}">Dismiss Report</a>` : ''}
                            </div>
                        </div>
                        <div class="float-end ms-2">
                            <span class="badge rounded-pill ${getBadgeClass(report.status)} font-size-12" id="k-rep-status">${report.status}</span>
                        </div>
                        <div>
                            <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark" id="k-rep-type">${report.case_type}</a></h5>
                            <strong><p class="mb-1" id="k-report-reporter">${report.reporter}</p></strong>
                            <strong><p class="mb-1" id="k-report-contact">${report.contact}</p></strong>
                            <p class="text-muted mb-1" id="k-report-createdAt">${report.created_at}</p>
                            <p class="text-muted mb-4" id="k-report-address">${report.address}</p>
                        </div>
                        <div class="text">
                            <h5 class="font-size-15 mb-1" id="k-report-eventdesc">${report.eventdesc}</h5>
                        </div>
                        ${report.image_url ? `<img src="${report.image_url}" alt="No Image Sent" style="width: 200px;">` : ''}
                        ${deploymentInfoHtml}
                    </div>
                </div>
            `;

            if (report.status === 'pending') {
                $('#pending-reports').append(reportHtml);
            } else if (report.status === 'ongoing') {
                $('#ongoing-reports').append(reportHtml);
            } else if (report.status === 'resolved') {
                $('#resolved-reports').append(reportHtml);
            }
        });

        initDragula();
    }

    function initDragula() {
        dragula([document.getElementById("pending-reports"), document.getElementById("ongoing-reports"), document.getElementById("resolved-reports")])
            .on('drag', function(el, source) {
                el.dataset.sourceId = source.id;
                el.dataset.nextSiblingId = el.nextElementSibling ? el.nextElementSibling.getAttribute('id') : null;
            })
            .on('drop', function(el, target, source, sibling) {
                var reportId = el.getAttribute('data-report-id');
                var sourceId = el.dataset.sourceId;
                var targetId = target.id;
                var isValidMove = checkValidMove(sourceId, targetId);
                var nextSiblingId = el.dataset.nextSiblingId ? document.getElementById(el.dataset.nextSiblingId) : null;

                if (!isValidMove) {
                    swal.fire({
                        icon: 'error',
                        title: 'Invalid Move',
                        text: 'Reports can only move one step forward in the Kanban board.'
                    });

                    if (nextSiblingId) {
                        source.insertBefore(el, nextSiblingId);
                    } else {
                        source.appendChild(el);
                    }
                    return;
                }

                if (targetId === 'ongoing-reports') {
                    handleMoveToOngoing(reportId, el);
                } else if (targetId === 'resolved-reports') {
                    handleMoveToResolved(reportId);
                }
            });
    }

    function checkValidMove(sourceId, targetId) {
        const transitions = {
            'pending-reports': 'ongoing-reports',
            'ongoing-reports': 'resolved-reports',
        };
        return transitions[sourceId] === targetId;
    }

    function fetchKanbanData() { 
        fetch('/kanban-data') 
            .then(response => response.json())
            .then(data => renderKanban(data))
            .catch(error => console.error("Error fetching Kanban data:", error));
    }

    /** ----------------- Pending to Ongoing -------------------------- */
    function handleMoveToOngoing(reportId, el) {
        $('#select_rteam_modal').data('report-id', reportId).modal('show');

        $('#select_rteam_modal').off('hide.bs.modal').on('hide.bs.modal', function() {
            if (!$('#select_rteam_modal').data('deploymentCompleted')) {
                revertReportCardToPending(reportId, el);
            }
        });

        $('#assign-rteam').select2({ dropdownParent: $('#select_rteam_modal') });

        $('#select_rteam_form').off('submit').on('submit', function(e) {
            e.preventDefault();
            var selectedRteams = $('#assign-rteam').val();
            $('#select_rteam_modal').data('deploymentCompleted', true);

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
                    fetchKanbanData(); // Refresh Kanban board
                    $('#select_rteam_modal').modal('hide');
                    fetchTeamTbl();
                    fetchAvailableTeams()
                },
                error: function(error) {
                    console.error('Error:', error);
                }
            });
        });
    }

    function revertReportCardToPending(reportId, el) {
        var reportCard = $(`div[data-report-id="${reportId}"]`);
        reportCard.removeAttr('id').attr('id', `pending-rep-${reportId}`);
        $('#pending-reports').append(reportCard);
    }
    /** ----------------- Pending to Ongoing -------------------------- */

    /** ----------------- Ongoing to Resolved -------------------------- */
    function handleMoveToResolved(reportId) {
        $.ajax({
            url: '/kanban-report-resolve',
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                reportId: reportId,
                newStatus: 'resolved'
            },
            success: function(response) {
                fetchKanbanData(); // Refresh Kanban board
            },
            error: function(error) {
                console.error('Error:', error);
            }
        });
    }
    /** ----------------- Ongoing to Resolved -------------------------- */

    fetchKanbanData(); // Fetch Kanban data on page load

    function getBadgeClass(status) {
        switch(status) {
            case 'pending': return 'badge-soft-secondary';
            case 'ongoing': return 'badge-soft-warning';
            case 'resolved': return 'badge-soft-success';
            default: return '';
        }
    }



    var markers = {}; // Store individual markers
    var markersCluster = L.markerClusterGroup(); // Initialize marker cluster group

    fetchIncidentsAndMapMarkers(); // Call this on page load

    function fetchIncidentsAndMapMarkers() {
        fetch('/fetch-incidents-map')
        .then(response => response.json())
        .then(data => {
            data.forEach(incident => {
                var popupContent = `<strong>Reporter:</strong> ${incident.reporter}<br>
                                        <strong>Contact Number:</strong> ${incident.contact}<br>
                                        <strong>Case:</strong> ${incident.case_type}<br>
                                        <strong>Description:</strong> ${incident.eventdesc}`;

                if (incident.image_url) {
                    popupContent += `<br><img src="${incident.image_url}" style="max-width: 125px;">`;
                }

                // Determine the icon based on the incident type
                var iconUrl = '/markerIcons/default.png';
                var incidentType = incident.case_type.toLowerCase();

                if (incidentType.includes('mountain search')) {
                    iconUrl = '/markerIcons/mountain-marker.png';
                } else if (incidentType.includes('water search')) {
                    iconUrl = '/markerIcons/water-marker.png';
                } else if (incidentType.includes('trauma case')) {
                    iconUrl = '/markerIcons/trauma-marker.png';
                } else if (incidentType.includes('medical case')) {
                    iconUrl = '/markerIcons/medical-marker.png';
                }

                var customIcon = L.icon({
                    iconUrl: iconUrl,
                    iconSize: [75, 75],
                    iconAnchor: [37, 41],
                    popupAnchor: [1, -34]
                });

                const marker = L.marker([parseFloat(incident.lat), parseFloat(incident.long)], { icon: customIcon })
                                .bindPopup(popupContent);

                markersCluster.addLayer(marker); // Add marker to cluster
                markers[incident.id.toString()] = marker; // Store marker for direct access
            });

            map.addLayer(markersCluster); // Add cluster to map
        })
        .catch(error => console.log('Error fetching incidents:', error));
    }

    // Handle "View in Map" clicks directly to a marker
    $('.kanban-boards').on('click', '.view-in-map', function() {
        var reportId = $(this).data('report-id').toString();

        if (markers[reportId]) {
            var marker = markers[reportId];
            markersCluster.removeLayer(marker); // Temporarily remove from cluster

            map.flyTo(marker.getLatLng(), 18, { animate: true }); // Zoom into marker
            marker.openPopup(); // Show popup

            // Add marker directly to the map temporarily
            marker.addTo(map);

            setTimeout(() => {
                map.removeLayer(marker); // Remove from the map
                markersCluster.addLayer(marker); // Re-add to cluster
            }, 5000); // Re-add after 5s
        } else {
            console.log('Marker not found for report ID:', reportId);
        }
    });

    /** ----------------- Delete Report -------------------------- */
    $(document).on('click', '.dismiss-rep', function() {
        var reportId = $(this).attr('data-report-id');

        swal.fire({
            title: 'Are you sure?',
            text: 'You will not be able to recover this report!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, Dismiss it!',
            cancelButtonText: 'No, keep it',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/dismiss-report',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        reportId: reportId,
                        newStatus: 'dismissed'
                    },
                    success: function(response) {
                        console.log(`The report dismissed: ${reportId}`);
                        $(`div[data-report-id="${reportId}"]`).remove();

                        // Remove the marker from the map and the cluster
                        if (markers[reportId]) {
                            markersCluster.removeLayer(markers[reportId]);
                            delete markers[reportId];
                        }

                        fetchKanbanData(); // Refresh the Kanban board

                        swal.fire({
                            icon: 'success',
                            title: 'Report dismissed successfully',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    },
                    error: function(error) {
                        console.error('Error:', error);
                    }
                });
            }
        });
    });

    /** ----------------- Fetch Map Markers -------------------------- */
       /** ----------------- Fetch Available Teams -------------------------- */
       function fetchAvailableTeams() {
        $.ajax({
            url: '/get-available-teams',
            type: 'GET',
            success: function(response) {
                const selectRTeam = $('#assign-rteam');
                selectRTeam.empty(); // Clear the existing options

                if (response.available_RTeams.length > 0) {
                    response.available_RTeams.forEach(team => {
                        const option = `<option value="${team.id}">${team.team_name}</option>`;
                        selectRTeam.append(option); // Add new options
                    });
                } else {
                    const noTeamsOption = `<option value="" hidden>No Response Teams Available</option>`;
                    selectRTeam.append(noTeamsOption);
                }

                selectRTeam.trigger('change'); // Refresh the select dropdown
            },
            error: function(error) {
                console.error('Error fetching available teams:', error);
            }
        });
    }
    /** ----------------- Fetch Available Teams -------------------------- */

    // Handle toggle switch changes
    $(document).on('change', '.status-toggle', function() {
        const teamId = $(this).data('team-id');
        const newStatus = $(this).is(':checked') ? 'available' : 'busy';

        $.ajax({
            url: `/update-team-status/${teamId}`,
            type: 'POST',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                status: newStatus
            },
            success: function(response) {
                console.log('Team status updated successfully:', response);
                fetchTeamTbl(); // Refresh the team table
                fetchAvailableTeams()
                // Update the label text dynamically
                $(this).siblings('.form-check-label').text(newStatus.charAt(0).toUpperCase() + newStatus.slice(1));
            }.bind(this), // Bind `this` context to access the switch element



            error: function(error) {
                console.error('Error updating team status:', error);
            }
        });
    });

    $('#refetch-data').on('click', function() {
        // Clear all markers from the cluster and map
        markersCluster.clearLayers();
        markers = {}; // Clear the individual markers as well

        // Fetch incidents and map markers again
        fetchIncidentsAndMapMarkers();
        swal.fire({
            icon: 'success',
            title: 'Data Reinitialized Successfully',
            showConfirmButton: false,
            timer: 1500
        });
    });
    });//DOM
</script>
<!-- New Kanban Script -->

<!-- START Edit Pending Report Modal -->
<script>
$(document).ready(function() {
    // Initialize the select2 dropdown inside the modal
    $('#edit-preport-incident').select2({
        dropdownParent: $('#edit_preport_modal')
    });

    // Attach event delegation for opening the modal and fetching report details
    $(document).on('click', '.edit-preport-details', function() {
        var reportId = $(this).data('report-id');
        console.log('Report ID on edit click:', reportId); // Debugging line

        // Fetch report details via AJAX
        $.ajax({
            url: '/get-preport-details/' + reportId,
            type: 'GET',
            success: function(data) {
                // Populate the modal form with fetched data
                $('#edit-preport-report-id').val(reportId);
                $('#edit-preport-name').val(data.reporter);
                $('#edit-preport-contact').val(data.contact);
                $('#edit-preport-address').val(data.address);
                $('#edit-preport-eventdesc').val(data.eventdesc);

                // Update the select2 dropdown
                $('#edit-preport-incident').val(data.incident).trigger('change');

                // Update the image path correctly
                var imagePath = data.image_url || '/images/no-image.png'; // Fallback if no image
                $('#edit-preport-image').attr('src', imagePath);

                // Show the modal
                $('#edit_preport_modal').modal('show');
            },
            error: function(error) {
                console.error('Error fetching report details:', error);
            }
        });
    });

    // Handle form submission for editing the report
    $('#edit_preport_form').on('submit', function(e) {
        e.preventDefault(); // Prevent default form submission

        var reportId = $('#edit-preport-report-id').val();

        var formData = {
            _token: $('input[name="_token"]').val(),
            address: $('#edit-preport-address').val(),
            eventdesc: $('#edit-preport-eventdesc').val(),
            incident: $('#edit-preport-incident').val()
        };

        $.ajax({
            url: '/update-preport-details/' + reportId,
            type: 'POST',
            data: formData,
            success: function(response) {
                console.log('Report updated successfully:', response);

                // Update the corresponding Kanban card
                var reportCardSelector = '#pending-rep-' + reportId;

                $(reportCardSelector).find('#k-report-address').text(formData.address);
                $(reportCardSelector).find('#k-report-eventdesc').text(formData.eventdesc);

                var incidentTypeName = $('#edit-preport-incident option:selected').text();
                $(reportCardSelector).find('#k-rep-type').text(incidentTypeName);

                // Close the modal and reset the form
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
            }
        });
    });
});
</script>

<!-- Edit Pending Report Modal END -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        Echo.channel('reports')
            .listen('AddReport', (e) => {
                setTimeout(function() {
                    console.log('Incident report event received:', e);
                    toastr.warning('New Report has been Submitted', 'REMINDER!');
                }, 7000);
            });
    });
</script>
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
@endsection