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
                    <a href="<?php echo URLROOT;?>/students" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['isedit'] ? 'Edit' : 'Add';?> Student</div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/students/createupdate" method="post" name="studentform" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="sname" class="form-label">Student Name</label>
                                    <input type="text" name="sname" id="sname" class="form-control form-control-sm mandatory
                                           <?php echo inputvalidation($data['sname'],$data['sname_err'],$data['touched']);?>"
                                           value="<?php echo $data['sname'];?>" placeholder="eg Jane Doe" required>
                                    <span class="invalid-feedback"><?php echo $data['sname_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admno" class="form-label">Admision No</label>
                                    <input type="text" name="admno" id="admno" class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['admno'],$data['admno_err'],$data['touched']);?>"
                                           value="<?php echo $data['admno'];?>">
                                    <span class="invalid-feedback"><?php echo $data['admno_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Contact</label>
                                    <input type="text" id="contact" name="contact" class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['contact'],$data['contact_err'],$data['touched']);?>" 
                                           value="<?php echo $data['contact'];?>" placeholder="0700000000" maxlength="10" required>
                                    <span class="invalid-feedback"><?php echo $data['contact_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender</label>
                                    <select name="gender" id="gender" class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['gender'],$data['gender_err'],$data['touched']);?>" required>
                                        <option value="" selected disabled>Select Gender</option>
                                        <option value="1" <?php selectdCheck($data['gender'],1);?>>Male</option>
                                        <option value="2" <?php selectdCheck($data['gender'],2);?>>Female</option>
                                        <option value="3" <?php selectdCheck($data['gender'],3);?>>Not Specified</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['gender_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="idno" class="form-label">ID Number</label>
                                    <input type="text" name="idno" id="idno" class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['idno'],$data['idno_err'],$data['touched']);?>"
                                           value="<?php echo $data['idno'];?>" placeholder="eg 12345678">
                                    <span class="invalid-feedback"><?php echo $data['idno_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="admdate" class="form-label">Admision Date</label>
                                    <input type="date" name="admdate" id="admdate" class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['admdate'],$data['admdate_err'],$data['touched']);?>"
                                           value="<?php echo $data['admdate'];?>">
                                    <span class="invalid-feedback"><?php echo $data['admdate_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="course" class="form-label">Course</label>
                                <select name="course" id="course" 
                                        class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['course'],$data['course_err'],$data['touched']);?>">
                                    <option value="">Select course</option>
                                    <?php foreach($data['courses'] as $course) : ?>
                                        <option value="<?php echo $course->ID;?>" <?php selectdCheck($data['course'],$course->ID);?>><?php echo $course->CourseName;?></option>
                                    <?php endforeach;?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['course_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" 
                                       class="form-control form-control-sm 
                                       <?php echo inputvalidation($data['email'],$data['email_err'],$data['touched']);?>"
                                       value="<?php echo $data['email'];?>"
                                       placeholder="eg test@example.com">
                                <span class="invalid-feedback"><?php echo $data['email_err'];?></span>
                            </div>
                        </div><!-- /.row -->
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
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