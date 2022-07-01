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
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarAdmin" aria-expanded="false" aria-controls="sidebarAdmin" class="side-nav-link">
                    <i class="uil-shield-check"></i>
                    <span> Admin </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarAdmin">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="<?php echo URLROOT;?>/users">Users</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarMaster" aria-expanded="false" aria-controls="sidebarMaster" class="side-nav-link">
                    <i class="uil-plus-square"></i>
                    <span> Master Entries </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarMaster">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="<?php echo URLROOT;?>/items">Items</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarStocks" aria-expanded="false" aria-controls="sidebarStocks" class="side-nav-link">
                    <i class="uil-exchange"></i>
                    <span> Stocks Management </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarStocks">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="<?php echo URLROOT;?>/stocks/receipts">Receipts</a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT;?>/stocks/transfers">Transfers</a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT;?>/stocks/sales">Sales</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarStudents" aria-expanded="false" aria-controls="sidebarStudents" class="side-nav-link">
                    <i class="uil-graduation-hat"></i>
                    <span> Students </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarStudents">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="<?php echo URLROOT;?>/students/">Students</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarFinance" aria-expanded="false" aria-controls="sidebarFinance" class="side-nav-link">
                    <i class="uil-moneybag-alt"></i>
                    <span> Finance </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarFinance">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="<?php echo URLROOT;?>/finances/fees">Fees</a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT;?>/finances/expenses">Expenses</a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT;?>/finances/invoices">Invoices</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarReports" aria-expanded="false" aria-controls="sidebarReports" class="side-nav-link">
                    <i class="uil-receipt-alt"></i>
                    <span> Reports </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarReports">
                    <ul class="side-nav-second-level">
                        <li>
                            <a href="<?php echo URLROOT;?>/reports/fees">Fees Payments</a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT;?>/reports/feebalances">Fee Balances</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul><!-- End Sidebar -->
        <div class="clearfix"></div>
    </div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End -->