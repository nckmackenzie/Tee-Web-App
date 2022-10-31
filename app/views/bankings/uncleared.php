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
    <div class="spinner-container d-flex justify-content-center mt-2"></div>
    <div class="row">
        <div class="col-12">
            <div class="table-responsive"></div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/bankings/uncleared.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    