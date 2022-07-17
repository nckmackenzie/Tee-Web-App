<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Active students</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('student_msg','alert'); ?>
    <?php DeleteModal(URLROOT.'/students/delete','centermodal','Are you sure you want to delete this student?','id');?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/students/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Student</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="students-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Student Name</th>
                                    <th>Adm No</th>
                                    <th>Contact</th>
                                    <th>Gender</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['activestudents'] as $student) : ?>
                                    <tr>
                                        <td><?php echo $student->ID;?></td>
                                        <td><?php echo $student->StudentName;?></td>
                                        <td><?php echo $student->AdmisionNo;?></td>
                                        <td><?php echo $student->Contact;?></td>
                                        <td><?php echo $student->Gender;?></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/students/edit/<?php echo $student->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $student->ID;?>" 
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
<?php flash('student_flash_msg','toast'); ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/students/students.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    