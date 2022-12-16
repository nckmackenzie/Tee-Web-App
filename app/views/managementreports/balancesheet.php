<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12 mt-2" id="alerBox"></div>
        <div class="col-sm-12 col-md-4 mb-1">
            <label for="asof">Balance sheet as of</label>
            <input type="date" name="asof" id="asof" class="form-control form-control-sm mandatory">
            <span class="invalid-feedback"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12 col-md-2">
            <button type="button" class="btn btn-sm btn-success preview">Preview</button>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="spinner-container d-flex justify-content-center"></div>
        <div class="col-9 mx-auto mt-3">
            <div class="table-responsive"></div>
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/reports/management/balance-sheet.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    