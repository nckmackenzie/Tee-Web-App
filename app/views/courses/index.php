<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Courses</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('course_msg','alert');?>
    <?php DeleteModal(URLROOT .'/courses/delete','centermodal','Are you sure you want to delete this course?','id');?>                    

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/courses/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Course</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered w-100 nowrap dt-responsive" id="courses-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Course Name</th>
                                    <th>Course Code</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['courses'] as $course) : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $course->ID;?></td>
                                        <td><?php echo $course->CourseName;?></td>
                                        <td><?php echo $course->CourseCode;?></td>
                                        <td><span class="badge <?php echo badgeclasses($course->Status);?>"><?php echo $course->Status;?></span></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/courses/edit/<?php echo $course->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $course->ID;?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#centermodal"
                                                    ><i class="mdi mdi-delete"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>   
<?php flash('course_flash_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/courses/courses.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    