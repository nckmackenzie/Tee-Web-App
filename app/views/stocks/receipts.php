<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Receipts</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('receipt_msg','alert'); ?>
    <?php DeleteModal(URLROOT .'/stocks/deletereceipt','centermodal','Are you sure you want to delete this receipt?','id') ; ?>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <button type="button" class="btn btn-success mb-2 dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> Add Receipt <span class="caret"></span> </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?php echo URLROOT;?>/stocks/addgrn">GRN</a>
                                <a class="dropdown-item" href="<?php echo URLROOT;?>/stocks/addinter">Inter Transfer</a>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="receipts-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Receipt Date</th>
                                    <th>Type</th>
                                    <th>Grn No</th>
                                    <th>Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data['receipts'] as $receipt) : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $receipt->ID;?></td>
                                        <td><?php echo $receipt->ReceiptDate;?></td>
                                        <td><?php echo $receipt->ReceiptType;?></td>
                                        <td><?php echo $receipt->GrnNo;?></td>
                                        <td><?php echo $receipt->Value;?></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/stocks/receiptedit/<?php echo $receipt->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $receipt->ID;?>" 
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
<?php flash('receipt_toast_msg', 'toast'); ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/receipts.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    