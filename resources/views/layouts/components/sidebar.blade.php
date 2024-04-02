<div class="vertical-menu" >

    <div data-simplebar class="h-100">


        <div id="sidebar-menu">
            
            <ul class="metismenu list-unstyled" id="side-menu">

            <!-- Main Menu Start -->
            <li class="menu-title" key="t-menu">Main Menu</li>
                <li>
                    <a href="home" class="waves-effect">
                        <i class="bx bxs-dashboard"></i>
                        <span key="t-chat">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="incident_types" class="waves-effect">
                        <i class="bx bxs-file-plus"></i>
                        <span key="t-chat">Incident Types</span>
                    </a>
                </li>

                <li>
                    <a href="responseTeams" class="waves-effect">
                        <i class="bx bxs-ambulance"></i>
                        <span key="t-chat">Response Teams</span>
                    </a>
                </li>

                <li>
                    <a href="reports" class="waves-effect">
                        <i class="bx bxs-report"></i>
                        <span key="t-chat">View Reports</span>
                    </a>
                </li>
            <!-- Main Menu End -->

            <!-- Response Team Start -->
            <li class="menu-title" key="t-menu">Response Team Menu</li>
                <li>
                    <a href="/routing" class="waves-effect">
                        <i class="bx bxs-compass"></i>
                        <span key="t-chat">Routing</span>
                    </a>
                </li>
            <!-- Response Team End --> 

            <!-- access Menu Start -->
            <li class="menu-title" key="t-menu">Access</li>
                <li>
                    <a href="users" class="waves-effect">
                        <i class="bx bxs-user-badge"></i>
                        <span key="t-chat">User</span>
                    </a>
                </li>

                <li>
                    <a href="roles" class="waves-effect">
                        <i class="bx bxs-edit"></i>
                        <span key="t-chat">Role</span>
                    </a>
                </li>
            <!-- Access Menu End -->

                <li>
                <a class="waves-effect" href="{{ route('logout') }}" 
                            onclick="event.preventDefault();
                                                document.getElementById('logout-form').submit();">
                            
                        <i class="bx bxs-exit"></i>
                        <span key="t-chat">{{ __('Logout') }}</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>

        </div>

    </div>
    
</div>