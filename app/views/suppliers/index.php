<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Suppliers</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('supplier_msg','alert'); ?>
    <?php DeleteModal(URLROOT .'/supplers/delete','centermodal','Are you sure you want to delete this supplier','id');?>                    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 mb-2">
                            <a href="<?php echo URLROOT;?>/suppliers/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Supplier</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="suppliers-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Supplier Name</th>
                                    <th>Contact</th>
                                    <th>Contact Person</th>
                                    <th>Balance</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['suppliers'] as $supplier) : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $supplier->ID;?></td>
                                        <td><?php echo $supplier->SupplierName;?></td>
                                        <td><?php echo $supplier->Contact;?></td>
                                        <td><?php echo $supplier->ContactPerson;?></td>
                                        <td><?php echo $supplier->Balance;?></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/suppliers/edit/<?php echo $supplier->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $supplier->ID;?>" 
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
            </div>
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<?php flash('supplier_flash_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/suppliers/suppliers.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    