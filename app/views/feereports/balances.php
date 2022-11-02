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
    <div class="row my-1">
        <div class="col-md-4">
            <label for="semister">Semister</label>
            <select name="semister" id="semister" class="form-select form-select-sm mandatory">
                <option value="" selected disabled>Select semister</option>
                <?php foreach($data['semisters'] as $semister) : ?>
                    <option value="<?php echo $semister->ID;?>"><?php echo $semister->SemisterName;?></option>
                <?php endforeach; ?>
            </select>
            <span class="invalid-feedback startspan"></span>
        </div>
    </div> 
    <div class="row">
        <div class="col-md-2">
            <button class="btn btn-sm btn-primary preview">Preview</button>
        </div>
        <div class="spinner-container d-flex justify-content-center"></div>
        <div class="col-12 mt-2">
            <div class="table-responsive"></div>
        </div>
    </div> 
                       
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/reports/fees/balances.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    