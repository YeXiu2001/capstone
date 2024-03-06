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

                        <table class="text-center table table-hover" id="transtype_tbl">
                            <thead>
                                <tr>
                                    <th>Incident Type</th>
                                    <th>Created By</th>
                                    <th>Updated By</th>
                                    <th>Added At</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

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
                                    <input type="text" id="add-incident" name="add_incident" class="form-control" required placeholder="Add Incident" />
                                    <input data-repeater-delete type="button" class="btn btn-primary ms-2" value="Delete"/>
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
</div><!-- main container END -->

<script>
$('#add-submitBtn').click(function(e){
    e.preventDefault();

    // Initialize an empty array to hold the incidents data
    let incidents = [];

    // Iterate over each repeater item
    $('[data-repeater-item]').each(function() {
        let incident = $(this).find('[name="add_incident"]').val();
        if(incident) { // Make sure the field is not empty
            incidents.push(incident);
        }
    });

    // Create a new FormData object
    let formData = new FormData();
    // Append each incident to the FormData object
    incidents.forEach((incident, index) => {
        formData.append(`add_incident[${index}]`, incident);
    });

    // Proceed with the AJAX request
    $.ajax({
        type: 'POST',
        url: '/add-incident',
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            console.log(data.success);
            $('#add_incident_modal').modal('hide');
        },
        error: function(data) {
            console.log('An error occurred.');
            console.log(data);
        }
    });
});

</script>

@endsection