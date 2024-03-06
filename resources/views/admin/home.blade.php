@extends('layouts.app')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-12">
        
        <div class="row">
            <div class="col-lg-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Job View</p>
                                <h4 class="mb-0">14,487</h4>
                            </div>
                
                            <div class="flex-shrink-0 align-self-center">
                                <div data-colors='["--bs-success", "--bs-transparent"]' dir="ltr" id="eathereum_sparkline_charts"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top py-3">
                        <p class="mb-0"> <span class="badge badge-soft-success me-1"><i class="bx bx-trending-up align-bottom me-1"></i> 18.89%</span> Increase last month</p>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-lg-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">New Application</p>
                                <h4 class="mb-0">7,402</h4>
                            </div>
            
                            <div class="flex-shrink-0 align-self-center">
                                <div data-colors='["--bs-success", "--bs-transparent"]' dir="ltr" id="new_application_charts"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top py-3">
                        <p class="mb-0"> <span class="badge badge-soft-success me-1"><i class="bx bx-trending-up align-bottom me-1"></i> 24.07%</span> Increase last month</p>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-lg-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total Approved</p>
                                <h4 class="mb-0">12,487</h4>
                            </div>
            
                            <div class="flex-shrink-0 align-self-center">
                                <div data-colors='["--bs-success", "--bs-transparent"]' dir="ltr" id="total_approved_charts"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top py-3">
                        <p class="mb-0"> <span class="badge badge-soft-success me-1"><i class="bx bx-trending-up align-bottom me-1"></i> 8.41%</span> Increase last month</p>
                    </div>
                </div>
            </div><!--end col-->
            <div class="col-lg-3">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total Rejected</p>
                                <h4 class="mb-0">12,487</h4>
                            </div>
            
                            <div class="flex-shrink-0 align-self-center">
                                <div data-colors='["--bs-danger", "--bs-transparent"]' dir="ltr" id="total_rejected_charts"></div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body border-top py-3">
                        <p class="mb-0"> <span class="badge badge-soft-danger me-1"><i class="bx bx-trending-down align-bottom me-1"></i> 20.63%</span> Decrease last month</p>
                    </div>
                </div>
            </div><!--end col-->
        </div>
        
    </div>
</div>    
@endsection