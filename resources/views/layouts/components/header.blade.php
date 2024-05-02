<header id="page-topbar">

    <div class="navbar-header">
        <div class="d-flex">

            <div class="navbar-brand-box">
               

                <a href="/home" class="logo logo-light">
                    <span class="logo-sm">
                    <img src="{{url('assets/images/ligtas_icon.png')}}" alt="wala" width="50px" height="50px">
                    </span>
                    <span class="logo-lg">
                    <img src="{{url('assets/images/ligtas_land_nobg.png')}}" alt="wala" width="200px" height="110px">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

        </div>

        <div class="d-flex">



            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    
                    <span class="d-none d-xl-inline-block ms-1" key="t-henry">{{auth()->user()->name}}</span>
               
                </button>

            </div>

        </div>

    </div>

</header>