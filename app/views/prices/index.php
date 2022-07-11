<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Prices</h4>
            </div>
        </div>
    </div>     
    <!-- end page title -->
    <?php DeleteModal(URLROOT.'/prices/delete','centermodal','Are your you want to delete this price?','id');?>
    <?php flash('price_msg','alert'); ?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/prices/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Price</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="prices-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['prices'] as $price) : ?>
                                    <tr>
                                        <td><?php echo $price->ID;?></td>
                                        <td><?php echo $price->Title;?></td>
                                        <td><?php echo $price->StartDate;?></td>
                                        <td><?php echo $price->EndDate;?></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/prices/edit/<?php echo $price->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $price->ID;?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#centermodal"
                                                    ><i class="mdi mdi-delete"></i></button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>    
                    </div><!-- /.table-responsive -->
                </div><!-- /.card-body -->
            </div><!-- /.card -->
        </div>
    </div>
                        
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<?php flash('price_toast_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/prices.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    