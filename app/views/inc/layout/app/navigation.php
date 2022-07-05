<!-- ========== Left Sidebar Start ========== -->
<div class="leftside-menu">
    <!-- LOGO -->
    <a href="<?php echo URLROOT;?>/home" class="logo text-center logo-light">
        <span class="logo-lg">
            <img src="<?php echo URLROOT;?>/img/logos/logo_24_white.png" alt="Logo" height="16">
            <small class="fs-4 text-white">P.C.E.A T.E.E</small>
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

            <?php if((int)$_SESSION['usertypeid'] < 3 && (int)$_SESSION['ishead'] === 1){
                include_once 'navigation/admin_head.php';
            }elseif ((int)$_SESSION['usertypeid'] < 3 && (int)$_SESSION['ishead'] != 1){
                include_once 'navigation/admin_center.php';
            }elseif ((int)$_SESSION['usertypeid'] > 2 && (int)$_SESSION['ishead'] === 1) {
                include_once 'navigation/user_head.php';
            }elseif((int)$_SESSION['usertypeid'] > 2 && (int)$_SESSION['ishead'] != 1) {
                include_once 'navigation/user_center.php';
            }            
            ?>
        </ul><!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->