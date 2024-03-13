<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @include('layouts.components.head')
    
</head>
<body data-sidebar="dark">
    <div id="app">
    
        @include('layouts.components.header') 

        @include('layouts.components.sidebar')

        <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">

                        <!-- <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0 font-size-18">Dashboard</h4>

                                    <div class="page-title-right">
                                        <ol class="breadcrumb m-0">
                                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboards</a></li>
                                            <li class="breadcrumb-item active">Dashboard</li>
                                        </ol>
                                    </div>

                                </div>
                            </div>
                        </div> -->

                        <div class="row">
                            <div class="col-12">
                                <div class="card overflow-hidden">
                                    
                                    <div class="card-body pt-3">
                                        <div class="row">
                                           

                                        @yield('content')
                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    </div>

    @include('layouts.components.footer')

    @include('layouts.components.script')
    
</body>
</html>