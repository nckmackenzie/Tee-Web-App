<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Change Password</h4>
            </div>
        </div>
    </div>
    <?php flash('pwd_msg','alert'); ?>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/auth/change_password_act" method="post">
                        <div class="mb-3">
                            <label for="oldpassword" class="form-label">Old Password</label>
                            <input type="password" name="oldpassword" id="oldpassword" 
                                   class="form-control form-control-sm 
                                   <?php echo inputvalidation($data['oldpassword'],$data['oldpassword_err'],$data['touched']); ?>"
                                   value="<?php echo $data['oldpassword'];?>" placeholder="Old Password" required>
                            <span class="invalid-feedback"><?php echo $data['oldpassword_err'];?></span>
                        </div>
                        <div class="mb-3">
                            <label for="newpassword" class="form-label">New Password</label>
                            <input type="password" name="newpassword" id="newpassword" 
                                   class="form-control form-control-sm 
                                   <?php echo inputvalidation($data['newpassword'],$data['newpassword_err'],$data['touched']);?>"
                                   value="<?php echo $data['newpassword'];?>" placeholder="New Password" required>
                            <span class="invalid-feedback"><?php echo $data['newpassword_err'];?></span>
                        </div>
                        <div class="mb-3">
                            <label for="confirmpassword" class="form-label">Confirm Password</label>
                            <input type="password" name="confirmpassword" id="confirmpassword" 
                                   class="form-control form-control-sm 
                                   <?php echo inputvalidation($data['confirmpassword'],$data['confirmpassword_err'],$data['touched']);?>"
                                   value="<?php echo $data['confirmpassword'];?>" placeholder="Confirm Password" required>
                            <span class="invalid-feedback"><?php echo $data['confirmpassword_err'];?></span>
                        </div>
                        <div class="d-grid d-md-block">
                            <button class="btn btn-primary" type="submit"> Submit </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    