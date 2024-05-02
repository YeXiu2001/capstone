<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
    @vite(['resources/js/app.js'])
</head>
<style>
    .navbar{
        border-bottom: 2px solid red !important; /* Adding !important to ensure it overrides any existing border-bottom properties */
    }
/* 
    .header-container {
        padding-left: 20px;
        padding-right: 20px;
    }

    @media (max-width: 700px) {
        .header-container {
            padding-left: 0;
            padding-right: 0;
            margin: 0px 0px 24px
        }
    } */
</style>

<body>
<nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a href="/userHome" class="navbar-brand">LigtasGIS</a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarCollapse">
                <div class="navbar-nav">
                    <a href="/userHome" class="nav-item nav-link" active>Home</a>
                    <!-- <a href="#" class="nav-item nav-link">Profile</a>
                    <a href="#" class="nav-item nav-link">Contact</a> -->
                    <a href="{{ route('logout') }}" class="nav-item nav-link"  
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                         <i class="bx bx-log-out-circle"></i>
                        <span key="t-chat">{{ __('Logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </nav> 
    @yield('content')

@include('layouts.components.script')
</body>
</html>