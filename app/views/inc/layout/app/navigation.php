<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">
    <!-- LOGO -->
    <a href="<?php echo URLROOT;?>/home" class="logo text-center logo-light">
        <span class="logo-lg d-flex">
            <img src="<?php echo URLROOT;?>/img/logos/logo_24_white.png" alt="Logo" height="16">
            <small>P.C.E.A T.E.E</small>
        </span>
        <span class="logo-sm">
            <img src="<?php echo URLROOT;?>/img/logos/logo_24_white.png" alt="Logo" height="16">
        </span>
    </a>

    <div class="h-100" id="leftside-menu-container" data-simplebar>

        <!--- Sidemenu -->
        <ul class="side-nav">
            <li class="side-nav-title side-nav-item">Navigation</li>
            <li class="side-nav-item">
                <a href="<?php echo URLROOT;?>/home" class="side-nav-link">
                    <i class="uil-home-alt"></i>
                    <span> Dashboard </span>
                </a>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarDashboards" aria-expanded="false" aria-controls="sidebarDashboards" class="side-nav-link">
                    <i class="uil-shield-check"></i>
                    <span class="badge bg-success float-end">4</span>
                    <span> Admin </span>
                </a>
                <div class="collapse" id="sidebarDashboards">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="<?php echo URLROOT;?>/users">Users</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item">
                <a href="<?php echo URLROOT;?>/auth/change_password" class="side-nav-link">
                    <i class="uil-lock-alt"></i>
                    <span> Change Password </span>
                </a>
            </li>
        </ul><!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->