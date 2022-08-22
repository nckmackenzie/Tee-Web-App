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
    <?php flash('expense_msg','alert');?>
    <?php DeleteModal(URLROOT .'/expenses/delete','centermodal','Are you sure you want to delete this expense?','id');?>                    

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-8">
                            <a href="<?php echo URLROOT;?>/expenses/add" class="btn btn-success"><i class="mdi mdi-plus-circle me-2"></i> Add Expense</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="expenses-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Expense Date</th>
                                    <th>Voucher No</th>
                                    <th>Expense Account</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['expenses'] as $expense) : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $expense->ID;?></td>
                                        <td><?php echo $expense->ExpenseDate;?></td>
                                        <td><?php echo $expense->VoucherNo;?></td>
                                        <td><?php echo $expense->ExpenseAccount;?></td>
                                        <td><?php echo $expense->ExpenseAmount;?></td>
                                        <td>
                                            <a href="<?php echo URLROOT;?>/expenses/edit/<?php echo $expense->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <?php if((int)$_SESSION['usertypeid'] < 3) : ?>
                                                <button class="action-icon btn text-danger btndel"
                                                            data-id="<?php echo $expense->ID;?>" 
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
<?php flash('expense_flash_msg','alert');?> 
<script type="module" src="dist/js/pages/expenses/index.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    