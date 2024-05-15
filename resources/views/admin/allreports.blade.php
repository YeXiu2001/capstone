@extends('layouts.app')
@section('content')
<div class="mobile-menu-overlay"></div>
<div class="main-container">
<div class="float-end">
            <a href="/reports" class="btn btn-primary btn-sm">Go Back</a>
            </div>
    <div class="pd-ltr-20">

    <div class="title pb-20">
		    <h3 class="h3">All Reports</h3>

		</div>

    <div class="row">
            <div class="col">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Unresolved Reports</p>
                                <h4 class="mb-0" id="unresolvedReportsCount">{{ $unresolvedReportsCount }}</h4>
                            </div>
                
                            <div class="flex-shrink-0 align-self-center">
                                <i style="color: #b30019;" class="fs-1 bx bxs-user-x"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Responded</p>
                                <h4 class="mb-0" id="respondedReportsCount">{{ $respondedReportsCount }}</h4>
                            </div>
            
                            <div class="flex-shrink-0 align-self-center">
                                <i style="color: #3f532e;" class="fs-1 bx bxs-user-check"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Fake Reports</p>
                                <h4 class="mb-0" id="fakeReportsCount">{{ $fakeReportsCount }}</h4>
                            </div>
            
                            <div class="flex-shrink-0 align-self-center">
                                <i style="color: #000659;" class="fs-1 bx bxs-group"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="">
                        <form id="form_filter">
                                    @csrf
                                    <div class="row">

                                        <div class="col-lg-2 col-md-3">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Date From</label>
                                                <input type="date" class="form-control form-control-sm" id="date_from" name="date_from">
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <div class="mb-3">
                                                <label for="" class="form-label">Date To</label>
                                                <input type="date" class="form-control form-control-sm" id="date_to" name="date_to">
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <style>
                                                .reporter .select2-container .select2-selection--single {
                                                    height: 28px!important;
                                                    font-size: 10.5px;
                                                }
                                            </style>
                                            <div class="mb-3 reporter">
                                                <label for="reporter" class="form-label">Reporter</label>
                                                <select class="form-control select2" name="reporter" id="reporter" style="width:100%">
                                                    <option value="" selected>...</option>
                                                    @foreach($reporters as $reporter)
                                                        <option value="{{ $reporter->name }}">{{ $reporter->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <style>
                                                .itype .select2-container .select2-selection--single {
                                                    height: 28px!important;
                                                    font-size: 10.5px;
                                                }
                                            </style>
                                            <div class="mb-3 itype">
                                                <label for="itype" class="form-label">Incident Type</label>
                                                <select class="form-control select2" name="itype" id="itype" style="width:100%">
                                                    <option value="" selected>...</option>
                                                    @foreach($incident_types as $incident_type)
                                                        <option value="{{ $incident_type->id }}">{{ $incident_type->cases }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-2 col-md-3">
                                            <style>
                                                .rep_status .select2-container .select2-selection--single {
                                                    height: 28px!important;
                                                    font-size: 10.5px;
                                                }
                                            </style>
                                            <div class="mb-3 rep_status">
                                                <label for="rep_status" class="form-label">Status</label>
                                                <select class="form-control select2" name="rep_status" id="rep_status" style="width:100%">
                                                    <option value="" selected>...</option>
                                                    @foreach($incident_statuses as $status)
                                                        <option value="{{ $status }}">{{ ucfirst($status) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div style="margin-top:24px">
                                                <button type="submit" class="btn btn-primary btn-sm m-1">Search</button>
                                                <button type="button" id="resetBtn" class="btn btn-outline-primary btn-sm px-2 m-1">Clear</button>
                                            </div>
                                        </div>

                                    </div>
                                </form>
                                </div>
                        
                                @include('partials.allrepstbl', ['reports' => $reports])
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Include SweetAlert CSS and JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Initialize select2
    $('#reporter, #itype, #rep_status').select2();

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
    $('#form_filter').on('submit', function(e) {
        e.preventDefault();
        let formData = $(this).serialize();

        $.ajax({
            url: '/search-reports',
            type: 'GET',
            data: formData,
            success: function(data) {
                $('#reports-container').html(data);
            }
        });
    });

    $('#resetBtn').on('click', function() {
        $('#reporter, #itype, #rep_status').val(null).trigger('change.select2');
        $('#form_filter').trigger('reset');
        fetchReports(1); // Reload the first page of the table
    });

    // Change status
    $(document).on('change', '.change-status', function() {
        var reportId = $(this).data('report-id');
        var newStatus = $(this).val();

        $.ajax({
            url: '/update-report-status',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                report_id: reportId,
                status: newStatus
            },
            success: function(response) {
                Swal.fire({
                    title: 'Success!',
                    text: 'Status updated successfully',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });

                // Update data cards
                updateDataCards();
            },
            error: function(xhr) {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while updating the status',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });
    });

    function updateDataCards() {
        $.ajax({
            url: '/get-report-counts',
            method: 'GET',
            success: function(response) {
                $('#unresolvedReportsCount').text(response.unresolvedReportsCount);
                $('#respondedReportsCount').text(response.respondedReportsCount);
                $('#fakeReportsCount').text(response.fakeReportsCount);
            },
            error: function(xhr) {
                console.error('An error occurred while updating the data cards');
            }
        });
    }
});
</script>

@endsection
