<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Other Points</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('point_msg','alert');?>
    <?php DeleteModal(URLROOT .'/exams/deletepoints','centermodal','Are you sure you want to delete selected points?','id');?>                    
</div> <!-- container -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="<?php echo URLROOT;?>/exams/addpoints" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Points</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered dt-responsive w-100 nowrap" id="points-datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Book Title</th>
                                <th>Group Name</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['points'] as $point) : ?>
                                <tr>
                                    <td class="d-none"><?php echo $point->ID;?></td>
                                    <td><?php echo $point->BookTitle;?></td>
                                    <td><?php echo $point->GroupName;?></td>
                                    <td><?php echo $point->Category;?></td>
                                    <td>
                                        <a href="<?php echo URLROOT;?>/exams/editpoints/<?php echo $point->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                        <button class="action-icon btn text-danger btndel"
                                                data-id="<?php echo $point->ID;?>" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#centermodal"
                                                ><i class="mdi mdi-delete"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>  
<?php flash('point_flash_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/exams/points.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    