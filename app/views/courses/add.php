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
                    <a href="<?php echo URLROOT;?>/courses" class="btn btn-warning btn-sm ms-1">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['isedit'] ? 'Edit course' : 'Add course';?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/courses/createupdate" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="coursename" class="form-label">Course Name</label>
                                <input type="text" name="coursename" id="coursename"
                                       class="form-control form-control-sm mandatory
                                       <?php echo inputvalidation($data['coursename'],$data['coursename_err'],$data['touched']);?>" 
                                       value="<?php echo $data['coursename']; ?>"
                                       placeholder="eg Basic Diploma"> 
                                <span class="invalid-feedback"><?php echo $data['coursename_err'];?></span>      
                            </div>
                            <div class="col-12 mb-3">
                                <label for="coursecode" class="form-label">Course Code</label>
                                <input type="text" name="coursecode" id="coursecode"
                                       class="form-control form-control-sm 
                                       <?php echo inputvalidation($data['coursecode'],$data['coursecode_err'],$data['touched']);?>" 
                                       value="<?php echo $data['coursecode']; ?>"
                                       placeholder="eg CS/00125"> 
                                <span class="invalid-feedback"><?php echo $data['coursecode_err'];?></span>      
                            </div>
                            <?php if($data['isedit']) : ?>
                                <div class="col-12">
                                    <div class="form-check mb-2">
                                        <input type="checkbox" name="active" class="form-check-input" id="active" <?php checkstate($data['active']);?>>
                                        <label class="form-check-label" for="active">Active</label>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>" >
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>" >
                            <button class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    