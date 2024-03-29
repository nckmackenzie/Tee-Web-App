<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Exams</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('exam_msg','alert');?>
    <?php DeleteModal(URLROOT .'/exams/delete','centermodal','Are you sure you want to delete this exam?','id');?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/exams/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Exam</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="exams-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Exam Name</th>
                                    <th>Course</th>
                                    <th>Book</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['exams'] as $exam) : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $exam->ID;?></td>
                                        <td><?php echo $exam->ExamName;?></td>
                                        <td><?php echo $exam->CourseName;?></td>
                                        <td><?php echo $exam->BookName;?></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/exams/edit/<?php echo $exam->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $exam->ID;?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#centermodal"
                                                    ><i class="mdi mdi-delete"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<?php flash('exam_flash_msg','toast');?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/exams/exams.js"></script>                 
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    