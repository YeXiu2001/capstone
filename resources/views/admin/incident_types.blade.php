@extends('layouts.app')


@section('content')

<!-- add table for incident types -->
<!-- add incident types button sa taas sa table -->

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20">
    
    <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Incident Types</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    <div class="d-flex justify-content-end">
                            <!-- @can('Items-Write') -->
                                <button class="btn btn-primary btn-sm mb-2"data-bs-toggle="modal" data-bs-target="#add_incident_modal">Add Incident Type</button>
                            <!-- @endcan -->
                        </div>

                        @include('partials.incident_types_table', ['incident_types' => $incident_types])
                    </div>
                </div>
            </div>
        </div>
   
    </div><!-- pdLTR20 -->

<!-- Add Incident Type Modal -->
<div class="modal fade" id="add_incident_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add Incident Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add_incident_form" class="repeater" enctype="multipart/form-data">
                    <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            @csrf
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <input type="text" id="add_incident" name="add-incident" class="form-control" required placeholder="Add Incident" />
                                   <i data-repeater-delete class="bx bxs-trash" style="color:red; font-size:40px"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input data-repeater-create type="button" class="btn btn-success mt-3 mt-lg-0" value="Add Form"/>
                </form>
                
            </div><!-- modal body end -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add-submitBtn" class="btn btn-primary">Submit</button>
                </div>
        </div>
    </div>
</div>
<!-- ./ Add Incident Type Modal -->


<!-- Edit Incident Type Modal -->
<div class="modal fade" id="edit_incident_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Incident Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit_incident_form">
                            @csrf
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <input type="text" id="edit_incident" name="edit-incident" class="form-control" required/>
                                </div>
                            </div>
                </form>
                
            </div><!-- modal body end -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit-submitBtn" class="btn btn-primary">Submit</button>
                </div>
        </div>
    </div>
</div>
<!-- ./ Edit Incident Type Modal -->


</div><!-- main container END -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    /** ---------------------- this is fetching table ------------ */
$(document).on('click', '.pagination a', function(event) {
    event.preventDefault();

    let page = $(this).attr('href').split('page=')[1];
    fetchIncidentTable(page);
});

function fetchIncidentTable(page) {
    $.ajax({
        url: '/fetch-incident-tbl?page=' + page,
        success: function(data) {
            $('#incident_table_container').html(data);
        }
    });
}
/** ---------------------- this is fetching table ------------ */


    /*------------------adding incident type------------------------*/
    $('#add-submitBtn').click(function(e) {
        e.preventDefault();

        // Initialize an empty array to hold the incidents data
        let incidents = [];

        // Iterate over each repeater item to collect incidents
        $('[data-repeater-item]').each(function() {
            let incident = $(this).find('[id="add_incident"]').val();
            if (incident) { // Ensure the field is not empty
                incidents.push(incident);
            }
        });

        // Log the incidents array for debugging
        console.log(incidents);

        // Initialize a new FormData object
        let formData = new FormData();
        
        // Append the incidents array as a string (JSON format)
        formData.append('incidents', JSON.stringify(incidents));

        // AJAX request setup
        $.ajax({
            type: 'POST',
            url: '/add-incident',
            data: formData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log(response); // Assuming response is a JSON object
                if(response.success) {
                    console.log('Success:', response.success);
                    $('#add_incident_modal').modal('hide');
                    $('#add_incident_form').trigger('reset');
                    fetchIncidentTable();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.success,
                        showConfirmButton: false,
                        timer:1500,
                    });
                }
            },
            error: function(xhr, status, error) {
                console.log('An error occurred:', error.toString());
            }
        });
    });
/*------------------adding incident type------------------------*/



/** ---------------------- this is deleting incident type ------------ */
$(document).on('click', '.delete-btn', function() {
    var incidentId = $(this).data('id'); // Get the ID of the incident to delete

    // Confirm before deleting
    if (confirm('Are you sure you want to delete this incident?')) {
        $.ajax({
            url: '/delete-incident/' + incidentId, // Adjust the URL as necessary
            type: 'DELETE', // Use DELETE method
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
            },
            success: function(result) {
                // Remove the incident row from the table if deletion was successful
                if (result.success) {
                    $('button[data-id="' + incidentId + '"]').closest('tr').remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.success,
                        showConfirmButton: false,
                        timer:1500,
                    });

                    fetchIncidentTable();
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    }
});
/** ---------------------- this is deleting incident type ------------ */

/** ---------------------- this is editing incident type ------------ */
$(document).on('click', '.edit-btn', function() {
    var incidentId = $(this).data('id'); // Get the ID of the incident to edit

    // Fetch the incident details from the server
    $.ajax({
        url: '/get-incident-type/' + incidentId, // Adjust the URL as necessary
        type: 'GET', // Use GET method
        success: function(data) {
            // Populate the form fields with the incident details
            // Assuming the server returns incident data with 'cases' as one of the fields
            $('#edit_incident').val(data.cases);
            $('#edit_incident_form').attr('data-id', incidentId); // Store the ID in the form for later use
        }
    });
});

$('#edit-submitBtn').click(function(e) {
    e.preventDefault();
    var incidentId = $('#edit_incident_form').attr('data-id'); // Retrieve the ID stored in the form
    var incidentName = $('#edit_incident').val();

    $.ajax({
        url: '/update-incident-type/' + incidentId,
        type: 'POST',
        data: {
            'cases': incidentName,
            '_token': $('meta[name="csrf-token"]').attr('content') // CSRF token
        },
        success: function(response) {
            if(response.success) {
                console.log('Success:', response.success);
                // Optionally close the modal and refresh the table or part of the page that displays incidents
                $('#edit_incident_modal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.success,
                    showConfirmButton: false,
                    timer:1500,
                });
                fetchIncidentTable();
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.error,
                    showConfirmButton: false,
                    timer:1500,
                });
            }
        }
    });
});
}); //DOMContentLoaded END
</script>


@endsection