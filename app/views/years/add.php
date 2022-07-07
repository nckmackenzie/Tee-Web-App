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
                    <a href="<?php echo URLROOT;?>/years" class="btn btn-sm btn-danger">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['isedit'] ? 'Edit' : 'Add';?> Year</div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/years/createupdate" method="post" autocomplete="off" role="form">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Year Name</label>
                                    <input type="text" name="name" id="name" 
                                           class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['name'],$data['name_err'],$data['touched']);?>"
                                           value="<?php echo $data['name'];?>" placeholder="eg Year 2022" required>
                                    <span class="invalid-feedback"><?php echo $data['name_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="start" class="form-label">Start Date</label>
                                    <input type="date" name="start" id="start" 
                                           class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['start'],$data['start_err'],$data['touched']);?>"
                                           value="<?php echo $data['start'];?>" 
                                           required>
                                    <span class="invalid-feedback"><?php echo $data['start_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="end" class="form-label">End Date</label>
                                    <input type="date" name="end" id="end" class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['end'],$data['end_err'],$data['touched']);?>"
                                           value="<?php echo $data['end'];?>" required>
                                    <span class="invalid-feedback"><?php echo $data['end_err'];?></span>
                                </div>
                            </div>
                            <div class="d-grid d-md-block">
                                <input type="hidden" name="id" value=<?php echo $data['id'];?>>
                                <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                                <button class="btn btn-primary login-btn" type="submit"> Save </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    