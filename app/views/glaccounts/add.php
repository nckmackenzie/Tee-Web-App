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
                    <a href="<?php echo URLROOT;?>/glaccounts" class="btn btn-warning btn-sm ms-1">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/glaccounts/createupdate" autocomplete="off" method="post">
                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="accountname" class="form-label">Account Name</label>
                                    <input type="text" class="form-control form-control-sm mandatory 
                                        <?php echo inputvalidation($data['accountname'],$data['accountname_err'],$data['touched']);?>"
                                        value="<?php echo $data['accountname'];?>"
                                        placeholder="eg Accounts Receivable">
                                    <span class="invalid-feedback"><?php echo $data['accountname_err'];?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="accounttype" class="form-label">Account Type</label>
                                    <select name="accounttype" id="accounttype" class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['accounttype'],$data['accounttype_err'],$data['touched']);?>">
                                        <option value="">Select account type</option>
                                        <?php foreach($data['accounttypes'] as $accounttype) : ?>
                                            <option value="<?php echo $accounttype->ID;?>" <?php selectdCheck($data['accounttype'],$accounttype->ID);?>><?php echo $accounttype->AccountName;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['accounttype_err'];?></span>
                                </div>
                            </div>
                            <div class="d-grid d-md-block">
                                <input type="hidden" name="id" value="<?php echo $data['id'];?>" >
                                <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>" >
                                <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    