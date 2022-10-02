<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title"><?php echo $data['isedit'] === true ? 'Edit User' : 'Add User';?></h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-lg-9 mx-auto">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/users/create" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Full Name</label>
                                    <input type="text" id="username" name="username" 
                                           class="mandatory form-control form-control-sm 
                                           <?php echo inputvalidation($data['username'],$data['username_err'],$data['touched']);?>" 
                                           value="<?php echo $data['username'];?>" placeholder="eg Jane Doe" required>
                                    <span class="invalid-feedback"><?php echo $data['username_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" id="contact" name="contact" 
                                           class="mandatory form-control form-control-sm 
                                           <?php echo inputvalidation($data['contact'],$data['contact_err'],$data['touched']);?>" 
                                           value="<?php echo $data['contact'];?>"
                                           maxlength="10" 
                                           placeholder="eg 0700000000" required>
                                    <span class="invalid-feedback"><?php echo $data['contact_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="usertype" class="form-label">User Type</label>
                                    <select name="usertype" id="usertype" class="mandatory form-select form-select-sm">
                                        <option value="" disabled>Select user type</option>
                                        <option value="2" <?php selectdCheck($data['usertype'],2);?>>Administrator</option>
                                        <option value="4" <?php selectdCheck($data['usertype'],4);?>>Standard User</option>
                                        <?php if($_SESSION['examcenter']) : ?>
                                            <option value="5" <?php selectdCheck($data['usertype'],5);?>>Exam Marker</option>
                                        <?php endif; ?>
                                     </select>
                                    <span class="invalid-feedback"><?php echo $data['usertype_err'];?></span>
                                </div>
                            </div>
                            <?php if($data['isedit'] === false) : ?>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" id="password" name="password" 
                                           class="mandatory form-control form-control-sm 
                                           <?php echo inputvalidation($data['password'],$data['password_err'],$data['touched']);?>" 
                                           value="<?php echo $data['password'];?>"
                                           placeholder="Enter user password" required>
                                    <span class="invalid-feedback"><?php echo $data['password_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="confirmpassword" class="form-label">Confirm Password</label>
                                    <input type="password" id="confirmpassword" name="confirmpassword" 
                                           class="mandatory form-control form-control-sm 
                                           <?php echo inputvalidation($data['confirmpassword'],$data['confirmpassword_err'],$data['touched']);?>" 
                                           value="<?php echo $data['confirmpassword'];?>"
                                           placeholder="Confirm password" required>
                                    <span class="invalid-feedback"><?php echo $data['confirmpassword_err'];?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                            <?php if($data['isedit']) :?>
                                <div class="col-12">
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="active" class="form-check-input" id="active" <?php checkstate($data['active']);?>>
                                        <label class="form-check-label" for="active">Active</label>
                                    </div>
                                </div>
                            <?php endif;?>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value=<?php echo $data['id'];?>>
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <button class="btn btn-primary" type="submit"> Save </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    