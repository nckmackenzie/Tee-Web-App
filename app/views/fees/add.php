<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title"></h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/fees/createupdate" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="receiptno">Receipt No</label>
                                <input type="text" name="receiptno" id="receiptno" 
                                       class="form-control form-control-sm"
                                       value="<?php echo $data['receiptno'];?>" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="pdate">Payment Date</label>
                                <input type="date" name="pdate" id="pdate" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['pdate'],$data['pdate_err'],$data['touched']);?>"
                                       value="<?php echo $data['pdate'];?>">
                                <span class="invalid-feedback"><?php echo $data['pdate_err'];?></span>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="student">Student</label>
                                <select name="student" id="student" 
                                        class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['student'],$data['student_err'],$data['touched']);?>">
                                    <option value="" selected disabled>Select student</option>
                                    <?php foreach($data['students'] as $student) : ?>
                                        <option value="<?php echo $student->ID;?>" <?php selectdCheck($data['student'],$student->ID);?>><?php echo $student->StudentName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['semister_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="semister">Semister</label>
                                <select name="semister" id="semister" class="form-select form-select-sm 
                                <?php echo inputvalidation($data['semister'],$data['semister_err'],$data['touched']);?>">
                                    <option value="">Select semister</option>
                                    <?php foreach($data['semisters'] as $semister) : ?>
                                        <option value="<?php echo $semister->ID;?>" <?php selectdCheck($data['semister'],$semister->ID);?>><?php echo $semister->SemisterName;?></option>
                                    <?php endforeach; ?>   
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="balancebf">Balance B/f</label>            
                                <input type="text" name="balancebf" id="balancebf" 
                                       class="form-control form-control-sm" 
                                       value="<?php echo $data['balancebf'];?>"
                                       readonly>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="totalfee">Semister Fees</label>            
                                <input type="text" name="semisterfees" id="semisterfees" 
                                       class="form-control form-control-sm" 
                                       value="<?php echo $data['semisterfees'];?>"
                                       readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="totalpaid">Total Paid</label>            
                                <input type="text" name="totalpaid" id="totalpaid" 
                                       class="form-control form-control-sm" 
                                       value="<?php echo $data['totalpaid'];?>"
                                       readonly>        
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="balance">Balance</label>            
                                <input type="text" name="balance" id="balance" 
                                       class="form-control form-control-sm" 
                                       value="<?php echo $data['balance'];?>"
                                       readonly>        
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="amount">Amount Paid</label>
                                <input type="number" name="amount" id="amount" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['amount'],$data['amount_err'],$data['touched']);?>"
                                       value="<?php echo $data['amount'];?>"
                                       placeholder="eg 6,000">
                                <span class="invalid-feedback"><?php echo $data['amount_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="account">G/L Account</label>
                                <select name="account" id="account" 
                                        class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['account'],$data['account_err'],$data['account']);?>">
                                    <option value="" selected disabled>Select account</option>
                                    <?php foreach($data['accounts'] as $account) : ?>
                                        <option value="<?php echo $account->ID;?>" <?php selectdCheck($data['account'],$account->ID);?>><?php echo $account->AccountName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['account_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="paymethod">Payment Method</label>
                                <select name="paymethod" id="paymethod" 
                                        class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['paymethod'],$data['paymethod_err'],$data['paymethod']);?>">
                                    <option value="" selected disabled>Select pay method</option>
                                    <option value="1" <?php selectdCheck($data['paymethod'],1);?>>Cash</option>
                                    <option value="2" <?php selectdCheck($data['paymethod'],2);?>>Cheque</option>
                                    <option value="3" <?php selectdCheck($data['paymethod'],3);?>>Bank</option>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['paymethod_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="reference">Payment Reference</label>
                                <input type="text" name="reference" id="reference" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['reference'],$data['reference_err'],$data['touched']);?>"
                                       value="<?php echo $data['reference'];?>"
                                       placeholder="eg XM45221255X">
                                <span class="invalid-feedback"><?php echo $data['reference_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="narration">Narration</label>
                                <input type="text" name="narration" id="narration" 
                                       class="form-control form-control-sm"
                                       value="<?php echo $data['narration'];?>"
                                       placeholder="description on fee payment">
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