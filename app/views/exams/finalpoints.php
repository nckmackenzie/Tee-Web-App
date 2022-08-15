<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title"><?php echo $data['title'];?></h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="course">Course</label>
                        <select name="course" id="course" class="form-select form-select-sm mandatory">
                            <option value="" selected disabled>Select Course</option>
                            <?php foreach($data['courses'] as $course) : ?>
                                <option value="<?php echo $course->ID;?>"><?php echo $course->CourseName;?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback">Please select a course</span>
                    </div>
                    <div class="col-md-4">
                        <label for="book">Book</label>
                        <select name="book" id="book" class="form-select form-select-sm mandatory">
                            <option value="" selected disabled>Select Book</option>
                        </select>
                        <span class="invalid-feedback">Please select a book</span>
                    </div>
                    <div class="col-md-4">
                        <label for="group">Group</label>
                        <select name="group" id="group" class="form-select form-select-sm mandatory">
                            <option value="" selected disabled>Select Group</option>
                            <?php foreach($data['groups'] as $group) : ?>
                                <option value="<?php echo $group->ID;?>"><?php echo $group->GroupName;?></option>
                            <?php endforeach; ?>
                        </select>
                        <span class="invalid-feedback">Please select a book</span>
                    </div>
                    <div class="col-md-1 mt-2">
                        <button type="button" class="btn btn-sm btn-success w-full" data-id="btn">Preview</button>
                    </div>
                </div> <!-- /.row --> 
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/exams/final-points.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    