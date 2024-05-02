@extends('layouts.app')
@section('content')
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20">

    <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">All Citizens</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                           <!-- Modify search field and button layout -->
                        <div class="search-section d-flex mb-3">
                            <input type="text" id="searchField" class="form-control mr-2" placeholder="Search for Citizens">

                            <button class="btn btn-primary btn-sm" id="searchBtn">Search</button>
                            <button class="ms-2 btn btn-primary btn-sm" id="clearBtn">Clear</button>
                        </div>

                        @include('partials.allcitstbl', ['users' => $users])
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    /** ---------------------- this is fetching table ------------ */
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();

        let page = $(this).attr('href').split('page=')[1];
        fetchcitizens(page);
    });

    function fetchcitizens(page) {
        $.ajax({
            url: '/fetch-all-citizens?page=' + page,
            success: function(data) {
                $('#citizens-container').html(data);
            }
        });
    }
    /** ---------------------- this is fetching table ------------ */

    // Handle delete button clicks
    $(document).on('click', '.delete-btn', function(event) {
        event.preventDefault();

        let userId = $(this).data('id');

        // Confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "Do you want to Delete this User?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, decline'
        }).then((result) => {
            if (result.isConfirmed) {
                // AJAX request to decline the user's status
                $.ajax({
                    url: `/delete-user/${userId}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire('Success', 'User Deleted', 'success');
                            fetchcitizens(1); // Reload the first page to reflect the change
                        } else {
                            Swal.fire('Error', 'Failed to Delete', 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Error occurred while updating status', 'error');
                    }
                });
            }
        });
    });


    // Add an event listener to the search button
    $('#searchBtn').on('click', function() {
        const query = $('#searchField').val();

        if (!query) {
            alert('Please enter a search query');
            return;
        }

        // AJAX request to search and refresh the table
        $.ajax({
            url: '/search-citizens',
            method: 'POST',
            data: { query: query },
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for security
            },
            success: function(data) {
                $('#citizens-container').html(data);
            },
            error: function() {
                alert('Error occurred while searching');
            }
        });
    });

    $('#clearBtn').on('click', function() {
        $('#searchField').val(''); // Clear the search field
        fetchcitizens(); // Reload the table to its initial state
    });
});
</script>



@endsection