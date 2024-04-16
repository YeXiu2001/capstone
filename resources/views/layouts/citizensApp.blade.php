<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
    @vite(['resources/js/app.js'])
</head>
<style>
    .border-bottom {
        border-bottom: 1px solid red !important; /* Adding !important to ensure it overrides any existing border-bottom properties */
    }

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
    }
</style>

<body>
    <div class="header-container" style="position: sticky; top: 0;">
        <header class="d-flex flex-wrap justify-content-center py-3 mb-3 border-bottom">
            <a href="/" class="d-flex align-items-center mb-2 mb-md-0 me-md-auto text-dark text-decoration-none">
                <span class="fs-4">Incident Reporting</span>
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="#" class="nav-link" aria-current="page">Home</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Profile</a></li>
                <li class="nav-item"><a href="#" class="nav-link">About</a></li>
                <!-- <li class="nav-item"><a href="#" class="nav-link">FAQs</a></li> -->
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"  
                        onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">
                         <i class="bx bx-log-out-circle"></i>
                        <span key="t-chat">{{ __('Logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </header>
    </div> 
    @yield('content')




@include('layouts.components.script')
</body>
</html>