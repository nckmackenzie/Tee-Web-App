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
                    <a href="<?php echo URLROOT;?>/sales/saleswithbalances" class="btn btn-warning btn-sm ms-1">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div id="alerBox"></div>
            <div class="card">
                <div class="card-header">Receive Payment</div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/sales/receivepayment" method="post" id="form">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="paydate">Payment Date</label>
                                    <input type="date" name="paydate" id="paydate" 
                                           class="form-control form-control-sm mandatory"
                                           value="<?php echo $data['paydate'];?>">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="soldto">Sold To</label>
                                    <input type="text" name="soldto" id="soldto" 
                                           class="form-control form-control-sm"
                                           value="<?php echo $data['soldto'];?>"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="balance">Balance</label>
                                    <input type="text" name="balance" id="balance" 
                                           class="form-control form-control-sm"
                                           value="<?php echo $data['balance'];?>"
                                           readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="payment">Payment</label>
                                    <input type="number" name="payment" id="payment" 
                                           class="form-control form-control-sm mandatory"
                                           placeholder="eg 2,000">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="paymethod">Payment Method</label>
                                    <select name="paymethod" id="paymethod" class="form-select form-select-sm mandatory">
                                        <option value="" selected disabled>Select Pay Method</option>
                                        <option value="1" <?php selectdCheck($data['paymethod'],1);?>>Cash</option>            
                                        <option value="2" <?php selectdCheck($data['paymethod'],2);?>>M-Pesa</option>            
                                        <option value="3" <?php selectdCheck($data['paymethod'],3);?>>Bank</option> 
                                    </select>
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="reference">Reference</label>
                                    <input type="number" name="reference" id="reference" 
                                           class="form-control form-control-sm mandatory"
                                           placeholder="eg chq1234">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['saleid'];?>">
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <button class="btn btn-sm btn-primary save" type="submit"> Save </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/sales/balance-v1.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    