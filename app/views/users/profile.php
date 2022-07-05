<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">My profile</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/users/profile_act" method="post" autocomplete="off">
                        <div class="mb-3">
                            <label for="username" id="form-label">Full Name</label>
                            <input type="text" class="form-control form-control-sm 
                                   <?php echo inputvalidation($data['username'],$data['username_err'],$data['touched']);?>" 
                                   name="username"
                                   placeholder="Enter your full name" value="<?php echo $data['username'];?>">
                            <span class="invalid-feedback"><?php echo $data['username_err'];?></span>
                        </div>
                        <div class="mb-3">
                            <label for="contact" id="form-label">Full Name</label>
                            <input type="text" class="form-control form-control-sm 
                                   <?php echo inputvalidation($data['contact'],$data['contact_err'],$data['touched']);?>" 
                                   name="contact"
                                   maxlength="10"
                                   placeholder="Enter your full name" value="<?php echo $data['contact'];?>">
                            <span class="invalid-feedback"><?php echo $data['contact_err'];?></span>
                        </div>
                        <div class="d-grid d-md-block">
                            <button class="btn btn-primary" type="submit"> Update Profile </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                        
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    