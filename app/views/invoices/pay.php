<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">
                    <a href="<?php echo URLROOT;?>/invoices" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/invoices/payinvoice" method="post">
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="supplier">Supplier</label>
                                <input type="text" name="supplier" id="supplier" 
                                       class="form-control form-control-sm"
                                       value="<?php echo $data['supplier'];?>"
                                       readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="invoiceno">Invoice #</label>
                                <input type="text" name="invoiceno" id="invoiceno" 
                                       class="form-control form-control-sm"
                                       value="<?php echo $data['invoiceno'];?>"
                                       readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="invoiceamount">Invoice Value</label>
                                <input type="text" name="invoiceamount" id="invoiceamount" 
                                       class="form-control form-control-sm"
                                       value="<?php echo number_format($data['invoiceamount'],2);?>"
                                       readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="amountpaid">Amount Paid</label>
                                <input type="text" name="amountpaid" id="amountpaid" 
                                       class="form-control form-control-sm"
                                       value="<?php echo number_format($data['amountpaid'],2);?>"
                                       readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="balance">Balance</label>
                                <input type="text" name="balance" id="balance" 
                                       class="form-control form-control-sm"
                                       value="<?php echo number_format($data['balance'],2);?>"
                                       readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="currentamount">Current Payment</label>
                                <input type="text" name="currentamount" id="currentamount" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['currentamount'],$data['currentamount_err'],$data['touched']); ?>"
                                       value="<?php echo $data['currentamount'];?>"
                                       placeholder="eg 25,000">
                                <span class="invalid-feedback"><?php echo $data['currentamount_err'];?></span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="currentbalance">Closing Bal</label>
                                <input type="text" name="currentbalance" id="currentbalance" 
                                       class="form-control form-control-sm"
                                       value="<?php echo $data['currentbalance'];?>"
                                       readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="paymethod">Pay Method</label>
                                <select name="paymethod" id="paymethod" class="form-select form-select-sm mandatory">
                                    <option value=""selected disabled>Select pay method</option>
                                    <option value="1" <?php selectdCheck($data['paymethod'],1);?>>Cash</option>
                                    <option value="2" <?php selectdCheck($data['paymethod'],2);?>>Cheque</option>
                                    <option value="3" <?php selectdCheck($data['paymethod'],3);?>>Bank</option>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['currentamount_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="reference">Payment Reference</label>
                                <input type="text" name="reference" id="reference" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['reference'],$data['reference_err'],$data['touched']); ?>"
                                       value="<?php echo $data['reference'];?>"
                                       placeholder="eg chq001256">
                                <span class="invalid-feedback"><?php echo $data['reference_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="narration">Narration</label>
                                <input type="text" name="narration" id="narration" 
                                       class="form-control form-control-sm"
                                       value="<?php echo $data['narration'];?>"
                                       placeholder="eg Payment for invoice">
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <input type="hidden" name="supplierid " value="<?php echo $data['supplierid '];?>">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/invoices/invoice-pay.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    