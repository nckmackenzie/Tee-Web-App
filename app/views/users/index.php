<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Users</h4>
            </div>
        </div>
    </div>
    <?php DeleteModal(URLROOT.'/users/delete','centermodal','Are your you want to delete this year?','id'); ?>     
    <!-- end page title -->
    <?php flash('user_msg','alert'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/users/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add User</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                       <table class="table table-centered w-100 dt-responsive nowrap" id="users-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Full Name</th>
                                    <th>Contact</th>
                                    <th>User Type</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['users'] as $user): ?>
                                    <tr>
                                        <td class="d-none"><?php echo $user->ID;?></td>
                                        <td><?php echo $user->UserName;?></td>
                                        <td><?php echo $user->Contact;?></td>
                                        <td><?php echo $user->UserType;?></td>
                                        <td><span class="badge <?php echo $user->Status === 'Active' ? 'bg-success' : 'bg-danger';?>"><?php echo $user->Status;?></span></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/users/edit/<?php echo $user->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger btndel" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#centermodal"
                                                    onclick="rowFunction(this)"><i class="mdi mdi-delete"></i></button>
                                        </td>
                                    </tr>    
                                <?php endforeach; ?>    
                            </tbody>
                       </table>
                    </div><!--End of table responsive -->
                </div><!--End of card body-->
            </div><!--End of card-->
        </div><!--End of col-12-->
    </div> <!--End of row-->
                        
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<?php flash('user_toast_msg','toast');?>
<script src="<?php echo URLROOT; ?>/dist/js/pages/users.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    