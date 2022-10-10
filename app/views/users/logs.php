<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row mt-3">
        <div class="col-md-12" id="alertBox"></div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="startdate">Start Date</label>
                <input type="date" name="startdate" id="startdate" class="form-control form-control-sm mandatory">
                <span class="invalid-feedback"></span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="enddate">End Date</label>
                <input type="date" name="enddate" id="enddate" class="form-control form-control-sm mandatory">
                <span class="invalid-feedback"></span>
            </div>
        </div>
        <div class="col-md-2">
            <div class="mb-3">
                <label for=""></label>
                <button type="button" class="btn btn-sm btn-primary form-control preview">Preview</button>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="spinner-container d-flex justify-content-center"></div> 
    <div id="results" class="d-none">
        <table class="table table-sm table-bordered dt-responsive w-100 nowrap" id="logs-datatable">
            <thead class="table-light">
                <tr>
                    <th>Sale Date</th>
                    <th>Edit Date</th>
                    <th>Edited By</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>                   
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/users/logs.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    