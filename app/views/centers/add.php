<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">
                    <a href="<?php echo URLROOT;?>/centers" class="btn btn-sm btn-warning">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo !$data['isedit'] ? 'Create Center' : 'Edit Center';?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/centers/createupdate" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" name="name" id="name" 
                                           class="mandatory form-control form-control-sm 
                                           <?php echo inputvalidation($data['name'],$data['name_err'],$data['touched']);?>"
                                           value="<?php echo $data['name'];?>" 
                                           placeholder="eg TEE Kikuyu" required>
                                    <span class="invalid-feedback"><?php echo $data['name_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" name="contact" id="contact" 
                                           class="mandatory form-control form-control-sm 
                                           <?php echo inputvalidation($data['contact'],$data['contact_err'],$data['touched']);?>"
                                           value="<?php echo $data['contact'];?>" 
                                           placeholder="eg 0700000000" maxlength="10" required>
                                    <span class="invalid-feedback"><?php echo $data['contact_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" 
                                           class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['email'],$data['email_err'],$data['touched']);?>"
                                           value="<?php echo $data['email'];?>" 
                                           placeholder="eg test@example.com">
                                    <span class="invalid-feedback"><?php echo $data['email_err'];?></span>
                                </div>
                            </div>
                            <div class="d-grid d-md-block">
                                <input type="hidden" name="id" value=<?php echo $data['id'];?>>
                                <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                                <button class="btn btn-primary" type="submit"> Save </button>
                            </div>
                        </div><!--End of row -->
                    </form>
                </div>
            </div>
        </div>
    </div>
    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    