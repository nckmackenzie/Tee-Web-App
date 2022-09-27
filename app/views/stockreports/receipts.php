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
            <label for="start">Start Date</label>
            <input type="date" name="start" id="start" class="form-control form-control-sm">
            <span class="invalid-feedback startspan"></span>
        </div>
        <div class="col-md-3">
            <label for="end">End Date</label>
            <input type="date" name="end" id="end" class="form-control form-control-sm">
            <span class="invalid-feedback endspan"></span>
        </div>
        <div class="col-md-3">
            <label for="type">Receipt Type</label>
            <select name="type" id="type" class="form-select form-select-sm">
                <option value="0">All</option>
                <option value="1">GRN</option>
                <option value="2">Inter Transfer</option>
            </select>
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
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/reports/stocks/receiptsReport.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    