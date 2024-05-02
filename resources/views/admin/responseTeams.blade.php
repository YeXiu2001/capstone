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

                    <div class="float-end">
                            <div class="row">
                                @can('write-responseTeam')
                                <div class="col">
                                    <button class=" btn btn-primary btn-sm mb-2"data-bs-toggle="modal" data-bs-target="#add_t_modal">Add Teams</button>
                                </div>
                                @endcan
                                @can('write-members')
                                <div class="col">
                                    <button class="uniform-button btn btn-primary btn-sm mb-2" id="assign-memBtn" data-bs-toggle="modal" data-bs-target="#add_rtmembers_modal" style="width: 120px;">Add Members</button>
                                </div> 
                                @endcan
                            </div>
                            
                        </div>
                        @include('partials.teams_table', ['forteams' => $forteams])
                        
                        
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
                                    @forelse($vacantmembers as $user)
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

                    <select id="edit_team_status" name="edit_team_status" class="form-select select" required>
                            <option value="available">Available</option>
                            <option value="unavailable">Inactive</option>
                            <option value="busy">Busy</option>
                        </select>
                </form>

            </div><!-- modal body end -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="edit-t-submitBtn" class="btn btn-primary">Submit</button>
                </div>
        </div>
    </div>
</div><!-- ./ Edit Team Modal -->

<!-- View Member -->
<div class="modal fade" id="viewmem_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Team Members</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

                <div class="modal-body">
                    
                </div><!-- modal body end -->

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- View Member -->

</div><!-- main container -->


<script>
    document.addEventListener('DOMContentLoaded', function(){
        
    /** ------------ Initialization of select2 on loading ------------ */
        $('#members').select2({
        dropdownParent: $('#add_rtmembers_modal')
        });

        $('#team').select2({
            dropdownParent: $('#add_rtmembers_modal')
        });
    /** ------------ ./Initialization of select2 on loading ------------ */

    /** -------------------this is fetching table ----------------------- */
        // Function to fetch teams table
        function fetchTeamsTable(page) {
            $.ajax({
                url: '/fetch-teams-tbl?page=' + page,
                success: function(data) {
                    $('#teams_table_container').html(data);
                }
            });
        }
        // Pagination click event handler for teams table
        $(document).on('click', '#teams_pagination a', function(event) {
            event.preventDefault();
            let tpage = $(this).attr('href').split('page=')[1];
            fetchTeamsTable(tpage);
        });

    /** ------------------- ./this is fetching table ----------------------- */


    /** ------------------- fetch select2 teams options after edit team modal hide  ----------------------- */
            $('#edit_t_modal').on('hidden.bs.modal', function (e) {
                fetchTeamOptions(); // Fetch updated team options for Select2
            });
    /** ------------------- ./fetch select2 teams options after edit team modal hide  ----------------------- */

    /** ------------------- dynamic population of select2 ------------------ */
        function fetchTeamOptions() {
            $.ajax({
                url: '/fetch-teams-options', // Adjust URL as necessary
                type: 'GET',
                success: function(data) {
                    // Update options of the Select2 dropdown
                    let teamSelect = $('#team');
                    teamSelect.empty(); // Clear existing options
                    $.each(data.teams, function(key, value) {
                        teamSelect.append('<option value="' + value.id + '">' + value.team_name + '</option>');
                    });
                    teamSelect.trigger('change'); // Notify Select2 of changes
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(xhr.responseText);
                    alert('Failed to fetch teams. Please try again.');
                }
            });
        }
    /** ------------------- ./dynamic population of select2 ------------------ */

    /** ------------------- add team ------------------ */
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
    /** --------------------- ./add team --------------------- */

    /** --------------------------- Delete Team ---------------------------- */
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
    /** --------------------------- ./Delete Team ---------------------------- */

    /**-------------------EDit Team ------------------------------------ */
        $(document).on('click', '.edit-team-btn', function() {
            var teamid = $(this).data('id'); // Get the ID of the team to edit

            // Fetch the team details from the server
            $.ajax({
                url: '/get-team/' + teamid, // Adjust the URL as necessary
                type: 'GET', // Use GET method
                success: function(data) {
                    // Populate the form fields with the team details
                    $('#edit_team').val(data.team_name);
                    $('#edit_team_status').val(data.status);
                    $('#edit_t_form').attr('data-id', teamid); // Set the data-id attribute of the form
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
            
            var teamId = $('#edit_t_form').attr('data-id'); // Retrieve the ID stored in the form
            var status = $('#edit_team_status').val();
            var teamname = $('#edit_team').val();

            console.log(teamId, status, teamname);
            $.ajax({
                url: '/update-team/' + teamId,
                type: 'POST',
                data: {
                    'team_name': teamname,
                    'status': status,
                    '_token': $('meta[name="csrf-token"]').attr('content'),// CSRF token
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
                        fetchResponseTeamsTable();
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
    /**------------------- ./EDit Team ------------------------------------ */

    /** ----------------------- Add team members --------------------------- */
        $('#add_rtmembers_modal').on('shown.bs.modal', function (e) {
            // Clear selected options in the #team Select2 dropdown
            $('#team').val([]).trigger('change');
            $('#members').val([]).trigger('change');
        });

        //assign members
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
    /** ----------------------- ./Add team members --------------------------- */

    $(document).on('click', '.view-team-btn', function() {
    let teamId = $(this).data('id');

        // Fetch the team members using an AJAX call
        $.ajax({
            url: '/fetch-team-members/' + teamId, // Adjust URL as needed
            type: 'GET',
            success: function(data) {
                $('#viewmem_modal .modal-body').html(data);
                $('#viewmem_modal').modal('show');
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to fetch team members.',
                });
            }
        });
    });

    /** ----------------------- Delete team members --------------------------- */
        $(document).on('click', '.del-member-btn', function() {
        let memberId = $(this).data('id'); // Get the ID of the member to delete
        let teamId = $(this).closest('tr').data('team-id'); // Assuming you store the team ID in the table row

        // SweetAlert confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: 'This action will remove the member from the team.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it',
        }).then((result) => {
            if (result.isConfirmed) {
                // Make an AJAX request to delete the member
                $.ajax({
                    url: '/delete-teammem/' + memberId, // Adjust the URL as necessary
                    type: 'DELETE', // Use the DELETE method
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
                    },
                    success: function(response) {
                        if (response.success) {
                            // Display success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: response.success,
                                timer: 1500,
                                showConfirmButton: false,
                            });

                            // Refresh the team members table
                            fetchTeamMembersTable(teamId);
                        } else {
                            // Display error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.error,
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to remove member.',
                        });
                    }
                });
            }
        });
        });

        function fetchTeamMembersTable(teamId) {
            $.ajax({
                url: '/fetch-team-members/' + teamId,
                type: 'GET',
                success: function(data) {
                    $('#viewmem_modal .modal-body').html(data);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to fetch team members.',
                    });
                }
            });
        }


    /** ----------------------- ./Delete team members --------------------------------- */

    });//DOMContentLoaded
</script>


@endsection