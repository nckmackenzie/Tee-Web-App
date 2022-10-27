<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box my-1">
                <button type="button" class="btn btn-sm btn-info fs-4 btnleft"><i class="mdi mdi-chevron-left"></i></button>
                <button type="button" class="btn btn-sm btn-info fs-4 btnright"><i class="mdi mdi-chevron-right"></i></button>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-12" id="alerBox"></div>
        
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                <div class="spinner-container d-flex justify-content-center"></div>
                    <form action="" id="graduation-fees" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <label for="no">Receipt No</label>
                                <input type="text" class="form-control form-control-sm" name="receiptno" id="receiptno" readonly>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="paydate">Payment Date</label>
                                <input type="date" class="form-control form-control-sm mandatory" id="paydate" name="paydate">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="student">Student</label>
                                <select name="student" id="student" class="form-select form-select-sm mandatory">
                                    <option value="" selected disabled>Select student</option>
                                    <?php foreach($data['students'] as $student) : ?>
                                        <option value="<?php echo $student->ID;?>"><?php echo $student->StudentName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="group">Group</label>
                                <select name="group" id="group" class="form-select form-select-sm">
                                    <option value="" selected disabled>Select group</option>
                                    <?php foreach($data['groups'] as $group) : ?>
                                        <option value="<?php echo $group->ID;?>"><?php echo $group->GroupName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-3">
                                <label for="paid">Amount Paid</label>
                                <input type="number" class="form-control form-control-sm mandatory" 
                                       id="paid" name="paid" placeholder="eg 4000">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-5 mb-3">
                                <label for="account">G/L Account</label>
                                <select name="account" id="account" class="form-select form-select-sm mandatory">
                                    <option value="" selected disabled>Select g/l account</option>
                                    <?php foreach($data['accounts'] as $account) : ?>
                                        <option value="<?php echo $account->ID;?>"><?php echo $account->AccountName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="paymethod">Pay Method</label>
                                <select name="paymethod" id="paymethod" class="form-select form-select-sm mandatory">
                                    <option value="" selected disabled>Select pay method</option>
                                    <option value="1">Cash</option>
                                    <option value="2">Mpesa</option>
                                    <option value="3">Bank</option>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-6">
                                <label for="reference">Pay Reference</label>
                                <input type="text" class="form-control form-control-sm mandatory" 
                                       id="reference" name="reference" placeholder="eg OGA12125522X">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="isedit" id="isedit">
                            <button type="submit" class="btn btn-sm btn-primary btnsave">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                     
                   
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/fees/graduation.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    