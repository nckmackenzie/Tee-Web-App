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
    <?php flash('fee_msg','alert');?>
    <?php DeleteModal(URLROOT .'/fees/delete','centermodal','Are you sure you want to delete this fee?','id');?> 
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/fees/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Fee Payment</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive w-100 nowrap" id="fees-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Payment Date</th>
                                    <th>Receipt #</th>
                                    <th width="30%">Student</th>
                                    <th>Amount</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['fees'] as $fee)  : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $fee->ID;?></td>
                                        <td><?php echo $fee->PaymentDate;?></td>
                                        <td><?php echo $fee->ReceiptNo;?></td>
                                        <td><?php echo $fee->StudentName;?></td>
                                        <td><?php echo $fee->AmountPaid;?></td>
                                        <td>
                                            <?php if((int)$_SESSION['usertypeid'] <3 && (strtotime($fee->CurDateTime) - strtotime($fee->UpdatedOn)) < 86400) : ?>
                                                <a href="<?php echo URLROOT;?>/fees/edit/<?php echo $fee->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                <button class="action-icon btn text-danger btndel"
                                                        data-id="<?php echo $fee->ID;?>" 
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
<?php flash('fee_flash_msg','toast');?>
<script type="module" src="dist/js/pages/fees/index.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    