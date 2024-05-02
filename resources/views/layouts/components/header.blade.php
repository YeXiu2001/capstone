<header id="page-topbar">

    <div class="navbar-header">
        <div class="d-flex">

            <div class="navbar-brand-box">
                <a href="/home" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="" alt="" height="17">
                    </span>
                </a>

                <a href="/home" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="" alt="" height="19">
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