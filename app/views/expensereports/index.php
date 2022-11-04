<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box" id="alerBox"></div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row my-2">
        <div class="col-md-3 mb-2">
            <label for="type">Report Type</label>
            <select name="type" id="type" class="form-select form-select-sm mandatory">
                <option value="" selected disabled>Select report type</option>
                <option value="all">All Expenses</option>
                <option value="byaccount">By Expense Account</option>
            </select>
            <span class="invalid-feedback"></span>
        </div>
        <div class="col-md-3 mb-2">
            <label for="account">Expense Account</label>
            <select name="account" id="account" class="form-select form-select-sm" disabled>
                <option value="" selected disabled>Select report type</option>
                <?php foreach($data['accounts'] as $account) : ?>
                    <option value="<?php echo $account->ID;?>"><?php echo $account->AccountName;?></option>
                <?php endforeach; ?>
            </select>
            <span class="invalid-feedback"></span>
        </div>
        <div class="col-md-3 mb-2">
            <label for="from">From</label>        
            <input type="date" name="from" id="from" class="form-control form-control-sm mandatory">
            <span class="invalid-feedback"></span>
        </div>
        <div class="col-md-3 mb-2">
            <label for="to">To</label>        
            <input type="date" name="to" id="to" class="form-control form-control-sm mandatory">
            <span class="invalid-feedback"></span>
        </div>
        <div class="col-md-2">
            <button class="btn btn-sm btn-success preview">Preview</button>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="spinner-container d-flex justify-content-center"></div>
            <table class="responsive"></table>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/reports/expenses/index.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    