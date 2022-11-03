<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row mt-1">
        <div class="col-12">
            <div class="page-title-box" id="alerBox"></div>
            <a href="<?php echo URLROOT;?>/pettycashreceipts" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="" method="post" autocomplete="off" id="receipt-form">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="receiptno">Receipt No</label>
                                <input type="text" class="form-control form-control-sm" id="receiptno"
                                       value="<?php echo $data['receiptno'];?>" name="receiptno" readonly>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="receiptdate">Receipt Date</label>
                                <input type="date" class="form-control form-control-sm mandatory" id="receiptdate"
                                       value="" name="receiptdate">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="amount">Receipt Amount</label>
                                <input type="number" class="form-control form-control-sm mandatory" id="amount"
                                       value="" name="amount" placeholder="amount received">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="reference">Reference / Cheque No</label>
                                <input type="text" class="form-control form-control-sm mandatory" id="reference"
                                       value="" name="reference" placeholder="cheque no used to make withdrawal">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="narration">Description</label>
                                <input type="text" class="form-control form-control-sm" id="narration"
                                       value="" name="narration" placeholder="Provide breif description...Optional but highly recommended">
                                <span class="invalid-feedback"></span>
                            </div>
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
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/cashreceipts/add.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    