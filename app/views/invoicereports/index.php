<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title"></h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row mb-1">
        <div class="col-md-3 sm-m-3">
            <label for="type">Report Type</label>
            <select name="type" id="type" class="control form-select form-select-sm">
                <option value="" selected disabled>Select report</option>
                <option value="due">Due Invoices</option>
                <option value="balances">Invoices with balances</option>
                <option value="bydate">Invoices by date</option>
                <option value="bysupplier">Invoices by supplier</option>
            </select>
            <span class="invalid-feedback typespan"></span>
        </div>
        <div class="col-md-3 sm-m-3">
            <label for="supplier">Suppliers</label>
            <select name="supplier" id="supplier" class="control form-select form-select-sm" disabled>
                <option value="" selected disabled>Select supplier</option>
                <?php foreach($data['suppliers'] as $supplier) : ?>
                    <option value="<?php echo $supplier->ID;?>"><?php echo $supplier->SupplierName;?></option>
                <?php endforeach; ?>
            </select>
            <span class="invalid-feedback supplierspan"></span>
        </div>
        <div class="col-md-3 sm-m-3">
            <label for="start">Start Date</label>
            <input type="date" name="start" id="start" class="control form-control form-control-sm" disabled>
            <span class="invalid-feedback startspan"></span>
        </div>
        <div class="col-md-3 sm-m-3">
            <label for="end">End Date</label>
            <input type="date" name="end" id="end" class="control form-control form-control-sm" disabled>
            <span class="invalid-feedback endspan"></span>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-2">
            <button class="btn btn-sm btn-primary preview">Preview</button>
        </div>
        <div class="col-12 mt-2">
            <div class="table-responsive">
                <div id="results"></div>
            </div>
        </div>
    </div>                     
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/reports/invoices/dueInvoices.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    