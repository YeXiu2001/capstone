@extends('layouts.app')
@section('content')

<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20">

        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Pending Citizen Registrations</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @include('partials.manageuserstbl', ['pending_users' => $pending_users])
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Pending Edit Modal -->
<div class="modal fade" id="view_user_modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">User Account Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Ajax will append -->
            </div>

        </div><!-- modal body end -->
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(){
    /** ---------------------- this is fetching table ------------ */
    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();

        let page = $(this).attr('href').split('page=')[1];
        fetchPendingusersTable(page);
    });

    function fetchPendingusersTable(page) {
        $.ajax({
            url: '/fetch-pendingusers-tbl?page=' + page,
            success: function(data) {
                $('#manageusers_table_container').html(data);
            }
        });
    }
    /** ---------------------- this is fetching table ------------ */

    /** ------------------ Approve User ---------------------- */
    $(document).on('click', '.approve-btn', function() {
    var userId = $(this).attr('data-id');
    $.ajax({
        url: '/users/approve/' + userId,
        type: 'POST',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content'), // Ensure you have a meta tag for csrf-token in your head tag
        },
        success: function(response) {
            // Reload part of the page or show a success message
            alert("User approved successfully");
            fetchPendingusersTable()
        },
        error: function(error) {
            console.log(error);
            alert("Something went wrong!");
        }
    });
});

$(document).on('click', '.delete-btn', function() {
var userId = $(this).attr('data-id');
$.ajax({
    url: '/users/reject/' + userId,
    type: 'POST',
    data: {
        _token: $('meta[name="csrf-token"]').attr('content'), // Ensure you have a meta tag for csrf-token in your head tag
    },
    success: function(response) {
        // Reload part of the page or show a success message
        alert("User rejected successfully");
        fetchPendingusersTable()
    },
    error: function(error) {
        console.log(error);
        alert("Something went wrong!");
    }
});

});

$(document).on('click', '.view-btn', function() {
    var userId = $(this).data('id');
    $.ajax({
        url: '/users/details/' + userId,
        type: 'GET',
        success: function(data) {
            // Assuming 'data' contains the user details (name, email, contact, imagedir)
            let image =  data.id_card;
            $('#view_user_modal .modal-body').html(`
                <p><strong>Name:</strong> ${data.name}</p>
                <p><strong>Email:</strong> ${data.email}</p>
                <p><strong>Contact Number:</strong> ${data.contact}</p>
                <strong><p>ID Image:</strong></p>
                <img src="{{ asset('id_cards/${image}') }}" alt="No Image Available" style="width: 200px;">
            `);
        },
        error: function(error) {
            console.log(error);
            alert("Could not fetch user details");
        }
    });
});
});//DOM
</script>
@endsection