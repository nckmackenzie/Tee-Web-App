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
                    <a href="<?php echo URLROOT;?>/expenses" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
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
                    <form action="<?php echo URLROOT;?>/expenses/createupdate" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="edate">Expense Date</label>
                                <input type="date" name="edate" id="edate" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['edate'],$data['edate_err'],$data['touched']);?>"
                                       value="<?php echo $data['edate'];?>">
                                <span class="invalid-feedback"><?php echo $data['edate_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="voucherno">Voucher No</label>
                                <input type="text" name="voucherno" id="voucherno" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['voucherno'],$data['voucherno_err'],$data['touched']);?>"
                                       value="<?php echo $data['voucherno'];?>"
                                       placeholder="eg 1234">
                                <span class="invalid-feedback"><?php echo $data['voucherno_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="account">Expense Account</label>
                                <select name="account" id="account" 
                                        class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['account'],$data['account_err'],$data['touched']);?>">
                                    <option value="" selected disabled>Select Account</option>
                                    <?php foreach($data['accounts'] as $account): ?>
                                        <option value="<?php echo $account->ID;?>"><?php echo $account->AccountName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['account_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['amount'],$data['amount_err'],$data['touched']);?>"
                                       value="<?php echo $data['amount'];?>"
                                       placeholder="eg 1000">
                                <span class="invalid-feedback"><?php echo $data['amount_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="paymethod">Payment Method</label>
                                <select name="paymethod" id="paymethod" 
                                        class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['paymethod'],$data['paymethod_err'],$data['touched']);?>">
                                    <option value="" selected disabled>Select pay method</option>
                                    <option value="1" <?php selectdCheck($data['paymethod'],1);?>>Cash</option>
                                    <option value="2" <?php selectdCheck($data['paymethod'],2);?>>Cheque</option>
                                    <option value="3" <?php selectdCheck($data['paymethod'],3);?>>Bank</option>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['paymethod_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="reference">Reference</label>
                                <input type="text" name="reference" id="reference" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['reference'],$data['reference_err'],$data['touched']);?>"
                                       value="<?php echo $data['reference'];?>"
                                       placeholder="eg X45211225X">
                                <span class="invalid-feedback"><?php echo $data['reference_err'];?></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="narration">Narration</label>
                                <input type="text" name="narration" id="narration" 
                                       class="form-control form-control-sm"
                                       value="<?php echo $data['narration'];?>"
                                       placeholder="eg water bill">
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    