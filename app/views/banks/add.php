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
                    <a href="<?php echo URLROOT;?>/banks" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-12" id="alerBox"></div>
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="" id="addbank" autocomplete="off">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="bankname">Bank Name</label>
                                <input type="text" class="form-control form-control-sm mandatory" 
                                       name="bankname" id="bankname"
                                       placeholder="eg Equity Bank"
                                       value="<?php echo $data['bankname'];?>">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="accountno">Account No</label>
                                <input type="text" class="form-control form-control-sm" 
                                       name="accountno" id="accountno" 
                                       placeholder="eg 01102458455283"
                                       value="<?php echo $data['accountno'];?>">
                                <span class="invalid-feedback"></span>
                            </div>
                            <?php if(!$data['isedit']) : ?>
                                <div class="col-md-6 mb-3">
                                    <label for="openingbal">Opening Balance</label>
                                    <input type="number" class="form-control form-control-sm" 
                                        name="openingbal" id="openingbal"
                                        placeholder="200000">
                                    <span class="invalid-feedback"></span>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="asof">As Of</label>
                                    <input type="date" class="form-control form-control-sm" 
                                        name="asof" id="asof">
                                    <span class="invalid-feedback"></span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="d-grid d-md-block">
                            <button type="submit" class="btn btn-sm btn-primary save">Save</button>
                            <input type="hidden" name="id" id="id" value="<?php echo $data['id'];?>">
                            <input type="hidden" name="isedit" id="isedit" value="<?php echo $data['isedit'];?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/banks/add.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    