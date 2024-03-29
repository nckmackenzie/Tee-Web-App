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
                            <div class="col-md-12 mb-3">
                                <label for="examname" class="form-label">Exam Name</label>
                                <input type="text" name="examname" id="examname" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['examname'],$data['examname_err'],$data['touched']);?>"
                                       value="<?php echo $data['examname'];?>"
                                       placeholder="eg Paper 1">
                                <span class="invalid-feedback"><?php echo $data['examname_err'];?></span>
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
                                <label for="book" class="form-label">Book</label>
                                <select name="book" id="book" 
                                        class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['bookid'],$data['bookid_err'],$data['touched']);?>">
                                    <option value="">Select book</option>
                                    <?php if($data['touched'] && !empty($data['course']) || $data['isedit']) : ?>
                                        <?php foreach($data['books'] as $book) : ?>
                                            <option value="<?php echo $book->ID;?>" <?php selectdCheck($data['bookid'],$book->ID);?>><?php echo $book->BookName;?></option>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['bookid_err'];?></span>
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
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/exams/add-exam.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    