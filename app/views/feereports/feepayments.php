<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12 mt-2" id="alerBox"></div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row mb-1">
        <div class="col-md-3 sm-m-3">
            <label for="type">Report Type</label>
            <select name="type" id="type" class="form-select form-select-sm mandatory">
                <option value="all">All</option>
                <option value="bycourse">By Courses</option>
            </select>
            <span class="invalid-feedback startspan"></span>
        </div>
        <div class="col-md-3 sm-m-3">
            <label for="start">Start Date</label>
            <input type="date" name="start" id="start" class="form-control form-control-sm mandatory">
            <span class="invalid-feedback startspan"></span>
        </div>
        <div class="col-md-3">
            <label for="end">End Date</label>
            <input type="date" name="end" id="end" class="form-control form-control-sm mandatory">
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
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/reports/fees/fee-payments-v1.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    