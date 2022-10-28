<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box mt-2" id="alerBox"></div>
        </div>
    </div>     
    <!-- end page title --> 
    <form action="" autocomplete="off" id="invoices-form">
        <div class="row">
            <div class="col-md-2 mb-2">
                <label for="payid">Pay ID</label>
                <input type="text" class="form-control form-control-sm" id="payid" 
                    value="<?php echo $data['payid'];?>" readonly>
            </div>
            <div class="col-md-3 mb-2">
                <label for="paymethod">Pay Method</label>
                <select name="paymethod" id="paymethod" class="form-select form-select-sm mandatory">
                    <option value="1">Cash</option>
                    <option value="2">Mpesa</option>
                    <option value="3" selected>Cheque</option>
                </select>
                <span class="invalid-feedback"></span>
            </div>
            <div class="col-md-3 mb-2">
                <label for="paydate">Payment Date</label>
                <input type="date" name="paydate" id="paydate" class="form-control form-control-sm mandatory">
                <span class="invalid-feedback"></span>
            </div>
            <div class="col-md-2 mb-2">
                <label for="due">Total Due</label>
                <input type="text" class="form-control form-control-sm text-danger fw-bolder" id="due" 
                    value="<?php echo number_format($data['totaldue'],2);?>" readonly>
            </div>
            <div class="col-md-2 mb-2">
                <label for="total">Total Payment</label>
                <input type="text" class="form-control form-control-sm text-success fw-bolder" id="total" 
                    value="0.00" readonly>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 d-grid">
                <button class="btn btn-sm btn-primary btn-block">Save</button>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12">
                <table class="table table-sm dt-responsive w-100 nowrap" id="invoices-table">
                    <thead class="table-info">
                        <tr>
                            <th>Check</th>
                            <th class="d-none">Invoice ID</th>
                            <th class="d-none">SupplierId</th>
                            <th>Pay Reference</th>
                            <th>Supplier Name</th>
                            <th>Invoice No</th>
                            <th>Opening Bal</th>
                            <th>Payment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['invoices'] as $invoice) : ?>
                            <tr>
                                <td>
                                    <div class="form-check mb-2">
                                        <input type="checkbox" class="form-check-input chkbx" id="active">
                                    </div>
                                </td>
                                <td class="d-none"><input type="hidden" class="invoiceid" value="<?php echo $invoice->ID;?>"></td>
                                <td class="d-none"><input type="hidden" class="sid" value="<?php echo $invoice->SupplierId;?>"></td>
                                <td><input type="text" class="form-control form-control-sm payreferece" readonly></td>
                                <td><?php echo $invoice->Supplier;?></td>
                                <td><?php echo $invoice->InvoiceNo;?></td>
                                <td class="balance"><?php echo $invoice->OpeningBal;?></td>
                                <td><input type="number" class="form-control form-control-sm payment" readonly></td>
                            </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>   
    </form>                 
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/payments/add.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    