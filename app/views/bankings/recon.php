<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box mt-1" id="alerBox"></div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row mt-2">
        <div class="col-md-4 mb-2">
            <label for="sdate">From</label>
            <input type="date" name="sdate" id="sdate" class="form-control form-control-sm mandatory">
            <span class="invalid-feedback"></span>
        </div>
        <div class="col-md-4 mb-2">
            <label for="edate">To</label>
            <input type="date" name="edate" id="edate" class="form-control form-control-sm mandatory">
            <span class="invalid-feedback"></span>
        </div>
        <div class="col-md-4 mb-2">
            <label for="balance">Balance</label>
            <input type="number" name="balance" id="balance" 
                   class="form-control form-control-sm mandatory" placeholder="eg 150,000">
            <span class="invalid-feedback"></span>
        </div>
        <div class="d-grid d-md-block">
            <button class="btn btn-sm btn-success preview">Preview</button>
        </div>
    </div>
    <div class="spinner-container d-flex justify-content-center mt-2"></div>                    
    <div class="row">
        <div class="col-12 mt-2">
            <div class="table-responsive"></div>
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/bankings/recon.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    