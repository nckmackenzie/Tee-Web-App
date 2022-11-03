<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Petty cash receipts</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('pettycash_msg','alert');?>
    <?php DeleteModal(URLROOT .'/semisters/delete','centermodal','Are you sure you want to delete this semister','id') ;?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/pettycashreceipts/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> New Receipt</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="cashreceipts-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Receipt No</th>
                                    <th>Receipt Date</th>
                                    <th>Amount</th>
                                    <th>Reference</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['receipts'] as $receipt) : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $receipt->ID;?></td>
                                        <td><?php echo $receipt->ReceiptNo;?></td>
                                        <td><?php echo date('d-m-y',strtotime($receipt->TransactionDate));?></td>
                                        <td><?php echo number_format($receipt->Debit,2);?></td>
                                        <td><?php echo strtoupper($receipt->Reference);?></td>
                                        <td>
                                            <?php if((int)$_SESSION['usertypeid'] < 3 && (strtotime($receipt->CurDateTime) - strtotime($receipt->UpdatedOn)) < 86400) : ?>
                                                    <a href="<?php echo URLROOT;?>/pettycashreceipts/edit/<?php echo $receipt->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                    <button class="action-icon btn text-danger btndel"
                                                            data-id="<?php echo $receipt->ID;?>" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#centermodal"
                                                                ><i class="mdi mdi-delete"></i></button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div><!-- /.col-12 -->
    </div><!-- /.row -->                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<?php flash('pettycash_toast_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/cashreceipts/index.js"></script>                     
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    