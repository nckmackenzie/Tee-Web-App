<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Centers</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('center_msg','alert'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/centers/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add User</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="centers-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Center Name</th>
                                    <th>Contact</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($centers as $center): ?>
                                    <tr>
                                        <td><?php echo $center->ID;?></td>
                                        <td><?php echo $center->Contact;?></td>
                                        <td><?php echo $center->Email;?></td>
                                        <td><?php echo $center->Address;?></td>
                                        <td><span class="badge <?php echo badgeclasses($center->Status);?>"><?php echo $center->Status;?></span></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/centers/edit/<?php echo $center->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger"><i class="mdi mdi-delete"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>    
                            </tbody>
                        </table>
                    </div><!--End of table responsive -->
                </div><!--End of card-body -->
            </div><!--End of card -->
        </div><!--End col-12--->
    </div><!--End row-->                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<?php flash('center_msg','flash'); ?> 
<script src="<?php echo URLROOT;?>/dist/js/pages/centers.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    