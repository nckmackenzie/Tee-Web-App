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
            <li>
                <a href="<?php echo URLROOT;?>/userrights">Users rights</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/userrights/clone">Clone rights</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/users/logs">Sale edit logs</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/centers">Centers</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/years">Financial Year</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/glaccounts">G/L Accounts</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/courses">Courses</a>
            </li>
            <ul class="side-nav-second-level">
                <li>
                    <a href="<?php echo URLROOT;?>/suppliers">Suppliers</a>
                </li>
                <li>
                    <a href="<?php echo URLROOT;?>/semisters">Semisters</a>
                </li>
                <li>
                    <a href="<?php echo URLROOT;?>/banks">Banks</a>
                </li>
            </ul>
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
                <a href="<?php echo URLROOT;?>/books">Books</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/prices">Set Prices</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/stocks/receipts">Receipts</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/stocks/transfers">Transfers</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/stocks/returns">Returns</a>
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
            <li>
                <a href="<?php echo URLROOT;?>/groups/">Groups</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/groups/members">Group Members</a>
            </li>
        </ul>
    </div>
</li>
<li class="side-nav-item">
    <a href="<?php echo URLROOT;?>/sales" class="side-nav-link">
        <i class="uil-dollar-sign"></i>
        <span> Sales </span>
    </a>
</li>
<li class="side-nav-item">
    <a data-bs-toggle="collapse" href="#sidebarExams" aria-expanded="false" aria-controls="sidebarExams" class="side-nav-link">
        <i class="uil-clipboard-notes"></i>
        <span> Exams </span>
        <span class="menu-arrow"></span>
    </a>
    <div class="collapse" id="sidebarExams">
        <ul class="side-nav-second-level">
            <li>
                <a href="<?php echo URLROOT;?>/exams/receiptfromgroup">Receipt From Group</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/exams/receiptpostmarking">Receipt Post Marking</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/exams/points">Attendance/Exercise/Cat Points</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/exams/finalpoints">Final Points</a>
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
                <a href="<?php echo URLROOT;?>/fees/structure">Fee Structure</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/fees">Fee Payments</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/expenses">Expenses</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/budgets">Budgets</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/invoices">Invoices</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/journals">Journal Entries</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/fees/graduationfees">Graduation Fees</a>
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
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sidebarSecondLevel" aria-expanded="false" aria-controls="sidebarSecondLevel">
                    <span> Stock Reports </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sidebarSecondLevel">
                    <ul class="side-nav-third-level">
                        <li>
                            <a href="<?php echo URLROOT;?>/stockreports/receipts">Receipts Report</a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT;?>/stockreports/transfers">Transfers Report</a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT;?>/stockreports/stockmovement">Stock Movement</a>
                        </li>
                    </ul>
                </div>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/reports/salesreport">Sales Report</a>
            </li>
            <li>
                <a href="<?php echo URLROOT;?>/reports/feepayments">Fees Payments</a>
            </li>
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#invoiceReports" aria-expanded="false" aria-controls="invoiceReports">
                    <span> Invoice Reports </span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="invoiceReports">
                    <ul class="side-nav-third-level">
                        <li>
                            <a href="<?php echo URLROOT;?>/invoicereports">Invoices reports</a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT;?>/invoicereports/payments">Invoices payments</a>
                        </li>
                        <li>
                            <a href="<?php echo URLROOT;?>/invoicereports/statement">Supplier statement</a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</li>

