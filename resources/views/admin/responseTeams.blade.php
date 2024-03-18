@extends('layouts.app')
@section('content')
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20">
    
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Response Teams</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    <div class="d-flex justify-content-end">
                            <!-- @can('Items-Write') -->
                            <div class="row">
                                <button class="btn btn-primary btn-sm mb-2"data-bs-toggle="modal" data-bs-target="#add_t_modal">Add Teams</button>
                            </div>
                            <!-- @endcan -->
                        </div>
                        @include('partials.teams_table', ['forteams' => $forteams])
                        
                        
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                    <div class="d-flex justify-content-end">
                            <!-- @can('Items-Write') -->
                            <div class="row">
                                <button class="btn btn-primary btn-sm mb-2"data-bs-toggle="modal" data-bs-target="#add_rtmembers_modal">Assign Members</button>
                            </div>
                            <!-- @endcan -->
                        </div>
                        
                        @include('partials.responseTeams_table', ['rtmems' => $rtmems])
                    </div>
                </div>
            </div>
        </div>
   
    </div><!-- pdLTR20 -->

    <!-- Add Response Team Modal -->
    <div class="modal fade" id="add_rtmembers_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Response Teams</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add_rtmembers_form" >
                    <div class="row mb-2">
                        <div class="col-lg">
                            <div>
                            <label class="form-label">Select Team</label>
                                <select class="form-control" id="team" name="team" required style="width: 100%;">
                                    <option value="" disabled hidden selected>Select Team</option>
                                    @forelse($teams as $teams)
                                    <option value="{{$teams->id}}">{{$teams->team_name}}</option>
                                    @empty
                                    <option value="">No Response Teams Available</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg">
                            <div>
                                <label class="form-label">Add Members</label>
                                <select class="form-control" id="members" name="members[]"  multiple="multiple" required style="width: 100%;">
                                    <option value="" hidden disabled>Select Members</option>
                                    @forelse($users as $user)
                                    <option value="{{$user->id}}">{{$user->name}}</option>
                                    @empty
                                    <option value="">No Members Available</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
                
            </div><!-- modal body end -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="add-rtmem-submitBtn" class="btn btn-primary">Submit</button>
                </div>
        </div>
    </div>
</div><!-- ./ Add Response Team Modal -->


<!-- Add Team Modal -->
<div class="modal fade" id="add_t_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Add New Response Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="add_t_form"class="repeater" enctype="multipart/form-data">
                <div data-repeater-list="group-a">
                        <div data-repeater-item class="row">
                            @csrf
                            <div class="mb-2">
                                <div class="d-flex justify-content-between">
                                    <input type="text" id="add_team" name="add_team" class="form-control" required placeholder="Add Team" />
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
                    <button type="submit" id="add-t-submitBtn" class="btn btn-primary">Submit</button>
                </div>
        </div>
    </div>
</div><!-- ./ Add Team Modal -->

<!-- Edit Team Modal -->
<div class="modal fade" id="edit_t_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Response Team</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit_t_form">
                    @csrf
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <input type="text" id="edit_team" name="edit_team" class="form-control" required />
                        </div>
                    </div>
                </form>

            </div><!-- modal body end -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit-t-submitBtn" class="btn btn-primary">Submit</button>
                </div>
        </div>
    </div>
</div><!-- ./ Edit Team Modal -->


</div><!-- main container -->

<!-- ----------------------- Add Response Team --------------------------- -->
<script>
document.addEventListener('DOMContentLoaded', function(){

/** ---------------------- this is fetching table ------------ */
    // Function to fetch teams table
    function fetchTeamsTable(page) {
        $.ajax({
            url: '/fetch-teams-tbl?page=' + page,
            success: function(data) {
                $('#teams_table_container').html(data);
            }
        });
    }

    // Function to fetch response teams table
    function fetchResponseTeamsTable(page) {
        $.ajax({
            url: '/fetch-teamsmembers-tbl?page=' + page,
            success: function(data) {
                $('#RTmem_table_container').html(data);
            }
        });
    }

    // Pagination click event handler for teams table
    $(document).on('click', '#teams_pagination a', function(event) {
        event.preventDefault();
        let tpage = $(this).attr('href').split('page=')[1];
        fetchTeamsTable(tpage);
    });

    // Pagination click event handler for response teams table
    $(document).on('click', '#rtmems_pagination a', function(event) {
        event.preventDefault();
        let rtpage = $(this).attr('href').split('page=')[1];
        fetchResponseTeamsTable(rtpage);
    });
/** ---------------------- this is fetching table ------------ */

        $('#add-t-submitBtn').click(function(e) {
        e.preventDefault();

        let teams = [];

         // Iterate over each repeater item to collect incidents
         $('[data-repeater-item]').each(function() {
            let team = $(this).find('[id="add_team"]').val();
            if (team) { // Ensure the field is not empty
                teams.push(team);
            }
        });
        console.log(teams);

        let rtformData = new FormData(document.getElementById('add_t_form'));
        rtformData.append('teams', JSON.stringify(teams));
        $.ajax({
            url: '/add-rteams',
            type: 'POST',
            data: rtformData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response){
                console.log(response);
                $('#add_t_modal').modal('hide');
                $('#add_t_form').trigger('reset');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.success,
                    showConfirmButton: false,
                    timer: 1500
                });

                fetchTeamsTable();
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseText,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        
        })
    });


       //initialize select2
       $('#members').select2({
        dropdownParent: $('#add_rtmembers_modal')
    });

    $('#team').select2({
        dropdownParent: $('#add_rtmembers_modal')
    });

    $('#add-rtmem-submitBtn').click(function(e) {
        e.preventDefault();
        let selectedTeamId = $('#team').val();
        let selectedMemberIds = $('#members').val(); // This will be an array of selected member IDs

        let memformData = new FormData(document.getElementById('add_rtmembers_form'));
        memformData.append('team', selectedTeamId);
        memformData.append('members', JSON.stringify(selectedMemberIds));

        $.ajax({
            url: '/add-member',
            type: 'POST',
            data: memformData,
            contentType: false,
            processData: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            
            success: function(response){
                console.log(response);
                $('#add_rtmembers_modal').modal('hide');
                $('#add_rtmembers_form').trigger('reset');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.success,
                    showConfirmButton: false,
                    timer: 1500
                });

                fetchResponseTeamsTable();
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: xhr.responseText,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        
        })
    });



//delete teams with members
$(document).on('click', '#del-teammem', function(){
    let memid = $(this).data('id');

  // Confirm before deleting
  if (confirm('Are you sure you want to disband this team?')) {
        $.ajax({
            url: '/delete-teammem/' + memid, // Adjust the URL as necessary
            type: 'DELETE', // Use DELETE method
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
            },
            success: function(result) {
                // Remove the incident row from the table if deletion was successful
                if (result.success) {
                    $('button[data-id="' + memid + '"]').closest('tr').remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.success,
                        showConfirmButton: false,
                        timer:1500,
                    });

                    fetchResponseTeamsTable();
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    }
});

//delete teams
$(document).on('click', '#del-team-btn', function(){
    let delteamId = $(this).data('id');

  // Confirm before deleting
  if (confirm('Are you sure you want to DELETE this team?')) {
        $.ajax({
            url: '/delete-teams/' + delteamId, // Adjust the URL as necessary
            type: 'DELETE', // Use DELETE method
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
            },
            success: function(result) {
                // Remove the incident row from the table if deletion was successful
                if (result.success) {
                    $('button[data-id="' + delteamId + '"]').closest('tr').remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: result.success,
                        showConfirmButton: false,
                        timer:1500,
                    });

                    fetchTeamsTable();
                } else {
                    alert('Something went wrong. Please try again.');
                }
            }
        });
    }
});


//edit teams
$(document).on('click', '.edit-team-btn', function() {
    var teamid = $(this).data('id'); // Get the ID of the team to edit

    // Fetch the team details from the server
    $.ajax({
        url: '/get-team/' + teamid, // Adjust the URL as necessary
        type: 'GET', // Use GET method
        success: function(data) {
            // Populate the form fields with the team details
            $('#edit_team').val(data.team_name); // Assuming 'team_name' is the field name returned from the server
            $('#edit_t_form').attr('data-id', teamid); // Store the ID in the form for later use

            // Trigger the modal to show up
            $('#edit_t_modal').modal('show');
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
            alert('Failed to fetch team details. Please try again.');
        }
    });
});

// Function to handle submission of edited team details
$('#edit-t-submitBtn').click(function(e) {
    e.preventDefault();
    var teamid = $('#edit_t_form').attr('data-id'); // Retrieve the ID stored in the form
    var teamname = $('#edit_team').val();

    $.ajax({
        url: '/update-team/' + teamid,
        type: 'POST',
        data: {
            'team_name': teamname,
            '_token': $('meta[name="csrf-token"]').attr('content') // CSRF token
        },
        success: function(response) {
            if(response.success) {
                console.log('Success:', response.success);
                // Optionally close the modal and refresh the table or part of the page that displays teams
                $('#edit_t_modal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: response.success,
                    showConfirmButton: false,
                    timer:1500,
                });
                fetchTeamsTable(); // Update the teams table after editing
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: response.error,
                    showConfirmButton: false,
                    timer:1500,
                });
            }
        },
        error: function(xhr, status, error) {
            // Handle error
            console.error(xhr.responseText);
            alert('Failed to update team. Please try again.');
        }
    });
});
});//DOMContentLoaded
</script>
<!-- ----------------------- Add team --------------------------- -->


@endsection