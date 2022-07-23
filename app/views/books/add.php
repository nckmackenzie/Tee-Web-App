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
                    <a href="<?php echo URLROOT;?>/books" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['isedit'] ? 'Edit' : 'Add' ;?> Book</div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/books/createupdate" method="post" autocomplete="off" role="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Book Name</label>
                                    <input type="text" name="name" id="name" class="mandatory form-control form-control-sm 
                                           <?php echo inputvalidation($data['name'],$data['name_err'],$data['touched']);?>"
                                           value="<?php echo $data['name'];?>" placeholder="eg Knowing God" required>
                                    <span class="invalid-feedback"><?php echo $data['name_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Book Code</label>
                                    <input type="text" name="code" id="code" class="mandatory form-control form-control-sm 
                                           <?php echo inputvalidation($data['code'],$data['code_err'],$data['touched']);?>"
                                           value="<?php echo $data['code'];?>" placeholder="Enter book code" required>
                                    <span class="invalid-feedback"><?php echo $data['code_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="author" class="form-label">Author</label>
                                    <input type="text" name="author" id="author" class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['author'],$data['author_err'],$data['touched']);?>"
                                           value="<?php echo $data['author'];?>" placeholder="eg J.I Packer" >
                                    <span class="invalid-feedback"><?php echo $data['author_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="publisher" class="form-label">Publisher</label>
                                    <input type="text" name="publisher" id="publisher" class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['publisher'],$data['publisher_err'],$data['touched']);?>"
                                           value="<?php echo $data['publisher'];?>" placeholder="eg Longhorn" >
                                    <span class="invalid-feedback"><?php echo $data['publisher_err'];?></span>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select name="course" id="course" class="form-select form-select-sm mandatory
                                        <?php echo inputvalidation($data['course'],$data['course_err'],$data['touched']);?>">
                                    <option value="" selected disabled>Select course</option>
                                    <?php foreach($data['courses'] as $course) : ?>
                                        <option value="<?php echo $course->ID;?>" <?php selectdCheck($data['course'],$course->ID);?>><?php echo $course->CourseName;?></option>
                                    <?php endforeach;?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['course_err'];?></span>
                            </div>
                            <?php if($data['allowedit'] || !$data['isedit']) : ?>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="openingbal" class="form-label">Opening Bal</label>
                                        <input type="number" name="openingbal" id="openingbal" class="mandatory form-control form-control-sm 
                                            <?php echo inputvalidation($data['openingbal'],$data['openingbal_err'],$data['touched']);?>"
                                            value="<?php echo $data['openingbal'];?>" placeholder="eg Longhorn" required>
                                        <span class="invalid-feedback"><?php echo $data['openingbal_err'];?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="asat" class="form-label">As At</label>
                                        <input type="date" name="asat" id="asat" class="mandatory form-control form-control-sm 
                                            <?php echo inputvalidation($data['asat'],$data['asat_err'],$data['touched']);?>"
                                            value="<?php echo $data['asat'];?>" placeholder="eg Longhorn" required>
                                        <span class="invalid-feedback"><?php echo $data['asat_err'];?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="col-12">
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="active" class="form-check-input" id="active" <?php checkstate($data['active']);?>>
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value=<?php echo $data['id'];?>>
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <input type="hidden" name="allowedit" value="<?php echo $data['allowedit'];?>">
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