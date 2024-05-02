<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.components.head')
    @vite(['resources/js/app.js'])
</head>
<style>
        .navbar {
            background-color: #022130;
        }
        .navbar-brand {
            display: flex;
            align-items: center; /* Align items vertically in the center */
            color: white; /* Set default text color to white */
        }
        .navbar-brand h5 {
            margin-left: 10px; /* Add some space between the logo and text */
            font-weight: normal; /* Keep the font weight normal by default */
        }
        .navbar-brand strong {
            color: #ffbc2e; /* Set a specific color for "GIS" text */
        }
        /* Add other styles as needed */
    </style>

<body>
<nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a href="/userHome" class="navbar-brand">
                <img src="assets/images/ligtas_icon.png" alt="Logo" width="50" height="50">
                <h5>LIGTAS<strong>GIS</strong></h5>
            </a>
            <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarCollapse">
                <div class="navbar-nav">
                    <a href="/userHome" class="nav-item nav-link" style="color: white;" active>Home</a>
                    <!-- Uncomment or add other links as needed -->
                    <a style="color: white;" href="{{ route('logout') }}" class="nav-item nav-link"
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