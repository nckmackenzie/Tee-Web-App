<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title"><?php echo $data['title'];?></h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('budget_msg','alert');?>
    <?php DeleteModal(URLROOT .'/budgets/delete','centermodal','Are you sure you want to delete this budget?','id');?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/budgets/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Budget</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="budgets-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Budget Name</th>
                                    <th>Fiscal Year</th>
                                    <th>Budget Value</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['budgets'] as $budget) : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $budget->ID;?></td>
                                        <td><?php echo $budget->BudgetName;?></td>
                                        <td><?php echo $budget->YearName;?></td>
                                        <td><?php echo number_format($budget->BudgetValue,2);?></td>
                                        <td>
                                            <?php if(!converttobool($budget->YearClosed)) : ?>
                                                <a href="<?php echo URLROOT;?>/budgets/edit/<?php echo $budget->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                <button class="action-icon btn text-danger btndel"
                                                        data-id="<?php echo $budget->ID;?>" 
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
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>  
<?php flash('budget_flash_msg','toast');?>
<script type="module" src="dist/js/pages/budgets/index.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    