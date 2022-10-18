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
<?php flash('semister_msg','alert'); ?>
<?php DeleteModal(URLROOT .'/semisters/delete','centermodal','Are you sure you want to delete this semister','id') ;?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="<?php echo URLROOT;?>/semisters/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> New Semister</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered dt-responsive w-100 nowrap" id="semisters-datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Semister Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['semisters'] as $semister) : ?>
                                <tr>
                                    <td class="d-none"><?php echo $semister->ID;?></td>
                                    <td><?php echo $semister->SemisterName;?></td>
                                    <td><?php echo date('d-m-y',strtotime($semister->StartDate));?></td>
                                    <td><?php echo date('d-m-y',strtotime($semister->EndDate));?></td>
                                    <td>
                                        <?php if((int)$_SESSION['usertypeid'] < 3 ): ?>
                                            <a href="<?php echo URLROOT;?>/semisters/edit/<?php echo $semister->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $semister->ID;?>" 
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
<?php flash('semister_flash_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/semisters/semister.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    