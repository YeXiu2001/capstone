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

<div class="mb-4">
    <div id="admin_map"></div>
</div>

<div class="row">
    <div class="col-lg-4">
        <div class="card mb-5" style="border-color: gray; border-width: 1px; border-style: solid;">
            <div class="card-body">
                <h4 class="card-title mb-4">Pending Incidents</h4>
                    <div id="pending-kanban" >
                        <div id="pending-reports" class="pb-5 task-list">
                            <!-- forelse Here to fetch data -->
                            @forelse($kanbanIncidents as $index => $reports)
                                @if($reports->status == 'pending')
                                <div class="card task-box" id="pending-rep-{{$index + 1}}" data-report-id="{{$reports -> id}}" style="border-color: gray; border-width: 1px; border-style: solid;">
                                    <div class="card-body">
                                        <div class="dropdown float-end">
                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item">View in Map</a>
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
        <div class="card mb-5" style="border-color: orange; border-width: 1px; border-style: solid;">
            <div class="card-body">
                <h4 class="card-title mb-4">In Progress</h4>
                    <div id="inprog-kanban">
                        <div id="ongoing-reports" class="pb-5 task-list">
                            <!-- forelse Here to fetch data -->
                            @forelse($kanbanIncidents as $index => $reports)
                                @if($reports->status == 'ongoing')
                            <div class="card task-box" id="inprog-rep-{{$index + 1}}" data-report-id='{{$reports->id}}' style="border-color: orange; border-width: 1px; border-style: solid;">
                                    <div class="card-body">
                                    <div class="dropdown float-end">
                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item">View in Map</a>
                                                    <a class="dropdown-item">View Complete Details</a>
                                                    <a class="dropdown-item edittask-details" href="#" id="report-id" data-bs-toggle="modal" data-bs-target="modal_id_here">Edit</a>
                                                    <a class="dropdown-item deletetask" href="#">Delete</a>
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
        <div class="card mb-5" style="border-color: green; border-width: 1px; border-style: solid;">
            <div class="card-body">
                <h4 class="card-title mb-4">Resolved</h4>
                    <div id="resolved-kanban">
                        <div id="resolved-reports" class="pb-5 task-list">
                           <!-- forelse Here to fetch data -->
                           @forelse($kanbanIncidents as $index => $reports)
                            @if($reports->status == 'resolved')
                            <div class="card task-box" id="resolved-rep-{{$index + 1}}" data-report-id='{{$reports->id}}' style="border-color: green; border-width: 1px; border-style: solid;">
                                <div class="card-body">
                                <div class="dropdown float-end">
                                                <a href="#" class="dropdown-toggle arrow-none" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="mdi mdi-dots-vertical m-0 text-muted h5"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <a class="dropdown-item">View in Map</a>
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
                    <div class="mb-2">
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
                            <label class="form-label" for="edit-preport-eventdesc">Description</label>
                            <textarea class="form-control" id="edit-preport-eventdesc" name="edit-preport-eventdesc" rows="4"></textarea>
                        </div>
                    </div>

                   <img src="asset('imagedir here')" alt="No image provided" style="width: 300px;">
                </form>

            </div><!-- modal body end -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit-preport-submitBtn" class="btn btn-primary">Submit</button>
                </div>
        </div>
    </div>
</div>
<!-- Pending Edit Modal -->

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


<script>
document.addEventListener('DOMContentLoaded', function(){
     fetch('/fetch-incidents-map')
    .then(response => response.json())
        .then(data => {
            data.forEach((incident, index) => {
                var popupContent = `<strong>Case:</strong> ${incident.case_type}<br>
                                    <strong>Description:</strong> ${incident.eventdesc}`;

                if (incident.image_url) {
                    popupContent += `<br><img src="${incident.image_url}" style="max-width: 125px;">`; // Adjust styling as needed
                }

                const marker = L.marker([parseFloat(incident.lat), parseFloat(incident.long)])
                                .addTo(map)
                                .bindPopup(popupContent);;
            });
        })
        .catch(error => console.log('Error:', error));

});
</script>


<script>
$(document).ready(function () {
    // Initialize Dragula with the Kanban board containers
    dragula([document.getElementById("pending-reports"), document.getElementById("ongoing-reports"), document.getElementById("resolved-reports")])
        .on('drop', function (el, target, source, sibling) {
            // Extract report ID from the element's data attribute
            var reportId = el.getAttribute('data-report-id');

            // Map the container IDs to report status
            var statusMappings = {
                'pending-reports': 'pending',
                'ongoing-reports': 'ongoing',
                'resolved-reports': 'resolved'
            };
            var newStatus = statusMappings[target.id];

            console.log(`Updating report ID ${reportId} to status '${newStatus}'`);
            // AJAX request to update the report status in the database
            $.ajax({
                method: 'POST',
                url: '/kanban-report-deploy', // Adjust this URL to your route for status updates
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token for Laravel
                    reportId: reportId,
                    status: newStatus
                },
                success: function (response) {
                    // On success, update the UI as needed
                    console.log('Report status updated successfully.');
                 // Define your status-to-class mapping
                    var statusToBadgeClass = {
                        'pending': 'badge-soft-secondary',
                        'ongoing': 'badge-soft-warning',
                        'resolved': 'badge-soft-success'
                    };

                    // Define your status-to-text mapping if necessary
                    var statusToText = {
                        'pending': 'Pending',
                        'ongoing': 'Ongoing',
                        'resolved': 'Resolved'
                    };

                    // Find the span within the dragged element and update its class and text
                    var statusBadge = $(el).find('span.badge');
                    // Remove all possible classes first
                    statusBadge.removeClass('badge-soft-secondary badge-soft-warning badge-soft-success');
                    // Add the new class based on the mapping
                    statusBadge.addClass(statusToBadgeClass[newStatus]);
                    // Update the badge text as well
                    statusBadge.text(statusToText[newStatus]);
                },
                error: function (error) {
                    // Handle error
                    console.error('Error updating report status:', error);
                }
            });
        });
});
</script>

<!-- Edit Pending Report Modal -->
<script>
$(document).ready(function() {
    $('.edit-preport-details').click(function() {
        $('#edit_preport_modal').modal('toggle');
    });

    $('.edit-preport-details').click(function() {
        var reportId = $(this).data('report-id');

        // Assuming you're embedding the report details in data attributes
        // If not, you would need to fetch the report details via AJAX here
        var report = $('#pending-rep-' + reportId);
        
        var name = report.find("#k-report-reporter").text();
        var contact = report.find("#k-report-contact").text();
        var address = report.find("#k-report-address").text();
        var description = report.find("#k-report-eventdesc").text();
        // Assume 'imagedir' holds the path to the image
        var imageSrc = report.find("img").attr('src');

        // Populate modal
        $('#edit-preport-modal').find('#edit-preport-name').val(name);
        $('#edit-preport-modal').find('#edit-preport-contact').val(contact);
        $('#edit-preport-modal').find('#edit-preport-address').val(address);
        $('#edit-preport-modal').find('#edit-preport-eventdesc').val(description);
        $('#edit-preport-modal').find('img').attr('src', imageSrc);

        // Store reportId somewhere in the modal for later
        $('#edit-preport-modal').data('report-id', reportId);
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
@endsection