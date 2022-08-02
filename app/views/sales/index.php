<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Sales</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
<?php flash('sale_msg','alert'); ?>
<?php DeleteModal(URLROOT .'/sales/delete','centermodal','Are you sure you want to delete this sale','id') ;?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="<?php echo URLROOT;?>/sales/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> New Sale</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered dt-responsive w-100 nowrap" id="sales-datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Sale ID</th>
                                <th>Sales Date</th>
                                <th>Sold To</th>
                                <th>Sale Value</th>
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
                                    <td><?php echo $sale->NetAmount;?></td>
                                    <td>
                                        <a href="<?php echo URLROOT;?>/sales/edit/<?php echo $sale->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                        <a href="<?php echo URLROOT;?>/sales/print/<?php echo $sale->ID;?>" class="action-icon btn text-primary"> <i class="mdi mdi-printer"></i></a>
                                        <button class="action-icon btn text-danger btndel"
                                                data-id="<?php echo $sale->ID;?>" 
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
    </div><!-- /.col-12 -->
</div><!-- /.row -->
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<?php flash('sale_flash_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/sales/sales.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    