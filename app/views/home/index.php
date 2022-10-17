<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-md-6">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Dashboard</h4>
            </div>
        </div>
    </div> 
    <?php flash('home_alert_msg','alert');?>    
    <!-- end page title --> 
   <?php if(ENV === 'development') : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        FOR <strong>TEST</strong> PURPOSES <strong>ONLY</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
   <?php endif; ?>
   
        <form action="<?php echo URLROOT;?>/home/changecenter" method="POST">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label for="center">Change Center</label>
                    <select name="center" id="center" class="form-select form-select-sm">
                        <option value="">Select center...</option>
                        <?php foreach($data['centers'] as $center) : ?>
                            <option value="<?php echo $center->ID;?>"><?php echo $center->CenterName;?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="d-grid d-md-block">
                <button type="submit" class="btn btn-sm btn-primary">Change Center</button>
            </div>
        </form>
    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php flash('home_msg','toast');?>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    