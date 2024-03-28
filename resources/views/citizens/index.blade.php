@extends('layouts.citizensApp')
<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<!-- Leaflet Dependencies -->
    <!-- leaflet CSS -->
    <link rel="stylesheet" href="{{url('assets/libs/leaflet/leaflet.css') }}"/>
        
    <!-- leaflet locate control -->
    <link rel="stylesheet" href="{{url('assets/js/maps/L.Control.Locate.min.css') }}" />

    <!-- leaflet geocoder for search -->
    <link rel="stylesheet" href="{{url('assets/js/maps/Control.Geocoder.css') }}" />



<!-- Leaflet Dependencies -->
@section('content')

 <!-- Make sure you put this AFTER Leaflet's CSS -->

    <style>
        #map{
            height: 85%;
        }

        .map-container{
            position: relative;
        }

        #report_witness{
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 1000;
        }

        #sos{

            position: absolute;
            bottom: 50px; /* Change this to vertically center it */
            left: 50%; /* Horizontally center */
            transform: translate(-50%, 50%); /* Adjust positioning to truly center */
            z-index: 1000;

            width: 70px; 
            height: 70px; 
            border-radius: 35px; 
            font-size: 20px; 
            text-align: center; 
        }

    </style>

<div class="map-container m-4">
    <div id="map"></div>
        <!-- <button class="btn btn-warning" id="report_witness" name="report_witness">I AM A WITNESS</button> -->
        <button class="btn btn-danger" id="sos" name="sos" data-bs-toggle="modal" data-bs-target="#myloc_report_modal">SOS</button>
</div>

<!-- Report on my Location Modal -->
<div class="modal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" id="myloc_report_modal">
      <div class="modal-dialog modal-dialog-centered" >
        <div class="modal-content">
          <div class="modal-header bg-light">
            <h5 class="modal-title">Add Report Description</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="cancel-modal"></button>
          </div>

          <div class="modal-body">
        <form id="addIncidentReportForm">
        @csrf
        <!-- lat and long -->
        <div class="input-group pt-2 col-auto">
          <span class="input-group-text">Lat and Lng</span>
          <input type="text" id="lat" name="lat" class="form-control" >
          <input type="text" id="long" name="long" class="form-control" >
        </div>
        <!-- lat and long -->

          <!-- address -->
          <div class="pt-2">
            <input type="text" id="address" name="address" class="form-control" placeholder="address" required>
          </div>
        <!-- address -->

        <!-- incidents -->
          <div class="input-group pt-2">
            <select class="form-control select2 col-10" id="incident" name="incident" required style="width: 100%;">
              <option value="" selected hidden>Select Incident</option>
              @foreach($incident_types as $incident)
              <option value="{{$incident->id}}">{{$incident->cases}}</option>
              @endforeach
            </select>
          </div>
        <!-- incidents -->



        <!-- event description -->
        <div class="pt-2">
        <textarea class="form-control pt-2" id="eventdesc" name="eventdesc" rows="5" placeholder = "Enter Event Description" required></textarea>
        </div>
        <!-- event description -->

        <!-- image -->
        <div class="pt-2">
        <input class="form-control" type="file" id="image" name="image" accept="image/*" capture="environment">
        </div>
        <!-- image -->
          </div><!-- Modal body --> 
          
          
          <div class="modal-footer bg-light">
            <button type="button" id="cancelmod" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-primary" id="myloc-submit" name="myloc-submit">Submit</button>
          </div>
          </form>
        </div>
      </div>
    </div>
<!-- Report on my Location Modal -->


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
map = L.map('map', {
    center:[8.241137015981792, 124.24375643514865], //lat, long
    zoom: 17,
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

//map layers/ control layer of basemaps and overlays
var maplayers = L.control.layers(basemaps).addTo(map);
//search Control plugin https://github.com/perliedman/leaflet-control-geocoder
L.Control.geocoder().addTo(map);
//leaflet-locate plugin https://github.com/domoritz/leaflet-locatecontrol
L.control.locate().addTo(map);

    });
</script>

<!-- Report on my location -->
<script>
  document.addEventListener('DOMContentLoaded', function(){
    function onLocationFound(e) {
        var lat = e.latlng.lat;
        var lng = e.latlng.lng;

        // Update input fields with latitude and longitude
        document.getElementById('lat').value = lat;
        document.getElementById('long').value = lng;

        // Construct the URL for Geoapify's Reverse Geocoding API
    var geoapifyUrl = `https://api.geoapify.com/v1/geocode/reverse?lat=${lat}&lon=${lng}&apiKey=9e0570cdff3b4149a9752d093a45f38b`;

// Make a request to the Geoapify Reverse Geocoding API
fetch(geoapifyUrl)
    .then(response => response.json())
    .then(data => {
        if(data && data.features && data.features.length > 0) {
            // Assume the first result is the most relevant
            var address = data.features[0].properties.formatted;
            // Update the address input field
            document.getElementById('address').value = address;
        } else {
            // Handle no address found or other errors
            console.error('No address found');
        }
    })
    .catch(error => {
        console.error('Error fetching address:', error);
    });
    }

    // Attach event listener for when location is found
    map.on('locationfound', onLocationFound);

    // Start location tracking when the modal is shown
    $('#myloc_report_modal').on('shown.bs.modal', function () {
        // Start locating
        map.locate({
            setView: true,
            maxZoom: 16,
            watch: true,
        });
    });

    $('#incident').select2({
            dropdownParent: $('#myloc_report_modal')
        });


    // Optionally, stop locating when the modal is hidden if continuous tracking is not needed
    $('#myloc_report_modal').on('hidden.bs.modal', function () {
        map.stop();
    });


/*------------------adding incident report------------------------*/
$('#addIncidentReportForm').submit(function(e) {
    e.preventDefault();

    // Initialize a new FormData object
    let formData = new FormData(this);

    // AJAX request setup
    $.ajax({
        type: 'POST',
        url: '/add-incident-report',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            console.log(response); // Assuming response is a JSON object
            if (response.success) {
                console.log('Success:', response.success);
                $('#myloc_report_modal').modal('hide');
                $('#addIncidentReportForm').trigger('reset');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.success,
                    showConfirmButton: false,
                    timer: 1500,
                });
            }
        },
        error: function(xhr, status, error) {
            console.error('An error occurred:', error.toString());
            let errorMessage = 'An error occurred while processing your request. Please try again later.';
            if (xhr.responseJSON && xhr.responseJSON.error) {
                errorMessage = xhr.responseJSON.error;
            }
            $('#myloc_report_modal').modal('hide');
            $('#addIncidentReportForm').trigger('reset');
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: errorMessage,
            });
        }
    });
});


});

    </script>
<!-- Report on my Location -->



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