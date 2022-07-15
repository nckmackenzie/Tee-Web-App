<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Transfers</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('transfer_msg','alert'); ?>
    <?php DeleteModal(URLROOT .'/stocks/deletetransfer','centermodal','Are you sure you want to delete this transfer?','id') ; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/stocks/addtransfer" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Transfer</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="transfers-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Transfer Date</th>
                                    <th>MtnNo</th>
                                    <th>Transfer To</th>
                                    <th>Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['transfers'] as $transfer) : ?>
                                    <tr>
                                        <td><?php echo $transfer->ID;?></td>
                                        <td><?php echo $transfer->TransferDate;?></td>
                                        <td><?php echo $transfer->MtnNo;?></td>
                                        <td><?php echo $transfer->TransferTo;?></td>
                                        <td><?php echo $transfer->TransferValue;?></td>
                                        <td>
                                            <?php if(!converttobool($transfer->Received)) :?>
                                                <a href="<?php echo URLROOT;?>/stocks/transferedit/<?php echo $transfer->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                <button class="action-icon btn text-danger btndel"
                                                        data-id="<?php echo $transfer->ID;?>" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#centermodal"
                                                        ><i class="mdi mdi-delete"></i></button>
                                            <?php endif;?>
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
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/transfers.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    