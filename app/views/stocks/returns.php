<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Returns</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('transfer_msg','alert'); ?>
    <?php DeleteModal(URLROOT .'/stocks/deletereturn','centermodal','Are you sure you want to delete this return?','id') ; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/stocks/addreturn" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Return</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="returns-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Return Date</th>
                                    <th>Return From</th>
                                    <th>Items Returned</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['returns'] as $return) : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $return->ID;?></td>
                                        <td><?php echo $return->ReturnDate;?></td>
                                        <td><?php echo $return->ReturnFrom;?></td>
                                        <td><?php echo $return->ItemsReturned;?></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/stocks/returnedit/<?php echo $return->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                <button class="action-icon btn text-danger btndel"
                                                        data-id="<?php echo $return->ID;?>" 
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
<?php flash('transfer_toast_msg', 'toast'); ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/returns.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    