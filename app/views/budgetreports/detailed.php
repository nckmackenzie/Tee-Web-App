<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box" id="alerBox"></div>
    </div>     
    <!-- end page title -->
    <div class="row my-1">
        <div class="col-md-4">
            <label for="year">Financial Year</label>
            <select name="year" id="year" class="form-select form-select-sm mandatory">
                <option value="" selected disabled>Select year</option>
                <?php foreach($data['years'] as $year) : ?>
                    <option value="<?php echo $year->ID;?>"><?php echo $year->YearName;?></option>
                <?php endforeach; ?>
            </select>
            <span class="invalid-feedback"></span>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <button class="btn btn-sm btn-success preview">Preview</button>
        </div>
    </div>  
    <div class="row">
        <div class="col-12 mt-2">
            <div class="spinner-container d-flex justify-content-center"></div>
            <div class="table-responsive"></div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/reports/budgets/detailed.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    