<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Supplier payments</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
<?php flash('payment_msg','alert'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="<?php echo URLROOT;?>/payments/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> New Payment</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered dt-responsive w-100 nowrap" id="payments-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>Payment Date</th>
                                <th>Payment ID</th>
                                <th>Supplier</th>
                                <th>Payment Value</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['payments'] as $payment) : ?>
                                <tr>
                                    <td><?php echo date('d-m-Y',strtotime($payment->TransactionDate));?></td>
                                    <td><?php echo $payment->PayId;?></td>
                                    <td><?php echo $payment->Supplier;?></td>
                                    <td><?php echo $payment->PayValue;?></td>
                                    <td>
                                        <?php $concatnated = $payment->PayId. '-'.$payment->SupplierId;?>
                                        <a href="<?php echo URLROOT;?>/payments/print/<?php echo $concatnated;?>"
                                           class="action-icon btn text-primary"> <i class="mdi mdi-printer"></i></a>
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
<?php flash('payment_flash_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/payments/index.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    