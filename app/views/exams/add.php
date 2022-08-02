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
                    <a href="<?php echo URLROOT;?>/Exams" class="btn btn-sm btn-warning ms-1">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
     <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/exams/createupdate" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="examname" class="form-label">Exam Name</label>
                                <input type="text" name="examname" id="examname" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['examname'],$data['examname_err'],$data['touched']);?>"
                                       value="<?php echo $data['examname'];?>"
                                       placeholder="eg Paper 1">
                                <span class="invalid-feedback"><?php echo $data['examname_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="examdate" class="form-label">Exam Date</label>
                                <input type="date" name="examdate" id="examdate" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['examdate'],$data['examdate_err'],$data['touched']);?>"
                                       value="<?php echo $data['examdate'];?>">
                                <span class="invalid-feedback"><?php echo $data['examdate_err'];?></span>
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
                            <div class="col-md-3 mb-3">
                                <label for="totalmarks" class="form-label">Total Marks</label>
                                <input type="number" name="totalmarks" id="totalmarks" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['totalmarks'],$data['totalmarks_err'],$data['touched']);?>"
                                       value="<?php echo $data['totalmarks'];?>"
                                       placeholder="100">
                                <span class="invalid-feedback"><?php echo $data['totalmarks_err'];?></span>        
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="passmark" class="form-label">Pass Mark</label>
                                <input type="number" name="passmark" id="passmark" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['passmark'],$data['passmark_err'],$data['touched']);?>"
                                       value="<?php echo $data['passmark'];?>"
                                       placeholder="eg 70">
                                <span class="invalid-feedback"><?php echo $data['passmark_err'];?></span>        
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>" >
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>" >
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
     </div>                   
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    