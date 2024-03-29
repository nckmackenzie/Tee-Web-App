<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<?php 
    function paymentstatus($status){
        if($status === 'Paid'){
            return 'bg-success';
        }elseif($status === 'Partially Paid'){
            return 'bg-warning';
        }elseif($status === 'Not Paid'){
            return 'bg-danger';
        }else{
            return 'bg-info';
        }
    }

    function paymentnotmade($amount,$balance){
        if(floatval($amount) === floatval($balance)){
            return true;
        }else{
            return false;
        }
    }
?>
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
    <?php flash('invoice_msg','alert');?>
    <?php DeleteModal(URLROOT.'/invoices/delete','centermodal','Are you sure you want to delete this invoice?','id');?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/invoices/add" class="btn btn-success"><i class="mdi mdi-plus-circle me-2"></i> Add Invoice</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered dt-responsive nowrap w-100" id="invoices-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Invoice Date</th>
                                    <th>InvoiceNo</th>
                                    <th>Supplier</th>
                                    <th>Amount</th>
                                    <th>Balance</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['invoices'] as $invoice) : ?>
                                    <tr>
                                        <td class="d-none"><?php echo $invoice->ID;?></td>
                                        <td><?php echo $invoice->InvoiceDate;?></td>
                                        <td><?php echo $invoice->InvoiceNo;?></td>
                                        <td><?php echo $invoice->SupplierName;?></td>
                                        <td><?php echo number_format($invoice->InvoiceAmount,2);?></td>
                                        <td><?php echo number_format($invoice->Balance,2);?></td>
                                        <td><span class="badge <?php echo paymentstatus($invoice->State);?>"><?php echo $invoice->State;?></span></td>
                                        <td>
                                            <?php if(paymentnotmade($invoice->InvoiceAmount,$invoice->Balance)) : ?>
                                                <a href="<?php echo URLROOT;?>/invoices/edit/<?php echo $invoice->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                            <?php endif; ?>
                                            
                                            <a href="<?php echo URLROOT;?>/invoices/print/<?php echo $invoice->ID;?>"
                                               class="action-icon btn text-primary"> <i class="mdi mdi-printer"></i></a>
                                            <?php if((int)$_SESSION['usertypeid'] < 2 && 
                                                      paymentnotmade($invoice->InvoiceAmount,$invoice->Balance)): ?>   
                                                <button class="action-icon btn text-danger btndel"
                                                        data-id="<?php echo $invoice->ID;?>" 
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
<?php flash('invoice_flash_msg','toast');?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/invoices/index.js"></script>                 
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    