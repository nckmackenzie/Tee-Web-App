<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Semisters</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
<?php flash('structure_msg','alert'); ?>
<?php DeleteModal(URLROOT .'/fees/deletestructure','centermodal','Are you sure you want to delete this structure','id') ;?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="<?php echo URLROOT;?>/fees/addstructure" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> New Fee Structure</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered dt-responsive w-100 nowrap" id="structure-datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Semister Name</th>
                                <th>Total Fees</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['structures'] as $structure) : ?>
                                <tr>
                                    <td class="d-none"><?php echo $structure->ID;?></td>
                                    <td><?php echo $structure->SemisterName;?></td>
                                    <td><?php echo $structure->TotalAmount;?></td>
                                    <td>
                                        <?php if((int)$_SESSION['usertypeid'] < 3 ): ?>
                                            <a href="<?php echo URLROOT;?>/fees/editstructure/<?php echo $structure->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $structure->ID;?>" 
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
<?php flash('structure_flash_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/fees/fee-structure.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    