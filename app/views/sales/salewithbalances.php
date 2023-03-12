<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Sales With Balances</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
<?php flash('sale_msg','alert'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered dt-responsive w-100 nowrap" id="sales-datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Sale ID</th>
                                <th>Sales Date</th>
                                <th>Sold To</th>
                                <th>Balance</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['sales'] as $sale) : ?>
                                <tr>
                                    <td class="d-none"><?php echo $sale->ID;?></td>
                                    <td><?php echo $sale->SalesID;?></td>
                                    <td><?php echo $sale->SalesDate;?></td>
                                    <td><?php echo $sale->SoldTo;?></td>
                                    <td><?php echo $sale->Balance;?></td>
                                    <td>
                                        <a href="<?php echo URLROOT;?>/sales/balancepayment/<?php echo $sale->ID;?>" class="btn btn-sm btn-success">Receive Payment</a>
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
<?php flash('sale_flash_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/sales/sales.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    