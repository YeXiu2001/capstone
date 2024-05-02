@extends('layouts.app')
@section('content')
<div class="mobile-menu-overlay"></div>
<div class="main-container">
    <div class="pd-ltr-20">

    <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">All Reports</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                           <!-- Modify search field and button layout -->
                        <div class="search-section d-flex mb-3">
                            <input type="text" id="searchField" class="form-control mr-2" placeholder="Search">

                            <button class="btn btn-primary btn-sm" id="searchBtn">Search</button>
                            <button class="ms-2 btn btn-secondary btn-sm" id="clearBtn">Clear</button>
                        </div>

                        @include('partials.allrepstbl', ['reports' => $reports])
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
$(document).ready(function() {
    /** ---------------------- Fetching Table ---------------------- */

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();

        let page = $(this).attr('href').split('page=')[1];
        fetchReports(page);
    });

    function fetchReports(page) {
        $.ajax({
            url: '/fetch-all-reports?page=' + page,
            success: function(data) {
                $('#reports-container').html(data);
            }
        });
    }
    /** ---------------------- Fetching Table ---------------------- */

    /** ---------------------- Search Reports ---------------------- */
    $('#searchBtn').on('click', function() {
        let query = $('#searchField').val(); // Get the search input

        $.ajax({
            url: '/search-reports?query=' + query,
            success: function(data) {
                $('#reports-container').html(data); // Update table content
            },
            error: function(error) {
                console.error('Error searching reports:', error);
            }
        });
    });

    $('#clearBtn').on('click', function() {
        $('#searchField').val(''); // Clear the search input
        fetchReports(1); // Reload the first page of the table
    });
    /** ---------------------- Search Reports ---------------------- */
});
</script>



@endsection