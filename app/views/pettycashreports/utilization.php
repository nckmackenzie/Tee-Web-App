<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row mt-1">
        <div class="col-12">
            <div class="page-title-box" id="alerBox"></div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-3 mb-2">
            <label for="from">From:</label>
            <input type="date" name="from" id="from" class="form-control form-control-sm mandatory">
            <span class="invalid-feedback"></span>
        </div>
        <div class="col-md-3 mb-2">
            <label for="to">To:</label>
            <input type="date" name="to" id="to" class="form-control form-control-sm mandatory">
            <span class="invalid-feedback"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <button type="button" class="btn btn-sm btn-success preview">Preview</button>
        </div>
        <div class="spinner-container d-flex justify-content-center"></div>
        <div class="col-md-12 mt-2">
            <div class="table-responsive"></div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/reports/cashreceipts/utilization.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    