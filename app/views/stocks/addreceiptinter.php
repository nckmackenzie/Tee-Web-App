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
                    <a href="<?php echo URLROOT;?>/stocks/receipts" class="btn btn-sm ms-1 btn-warning">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-12">
            <div class="alert-box"></div>
        </div>
        <div class="col-12">
            <form action="<?php echo URLROOT;?>/stocks/createupdatereceipt" method="post" autocomplete="off" name="receiptform">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label for="date" class="form-label">Receipt Date</label>
                                    <input type="date" name="date" id="date" 
                                          class="form-control form-control-sm mandatory 
                                          <?php echo inputvalidation($data['date'],$data['date_err'],$data['touched']);?>"
                                          value="<?php echo $data['date']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['date_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label for="reference" class="form-label">GRN No</label>
                                    <input type="text" name="reference" id="reference" 
                                           class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['reference'],$data['reference_err'],$data['touched']);?>" 
                                           value="<?php echo $data['reference'];?>" required>
                                    <span class="invalid-feedback"><?php echo $data['reference_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label for="mtn" class="form-label">MTN</label>
                                    <select name="mtn" id="mtn" class="form-select form-select-sm mandatory">
                                        <option value="">Select MTN</option>
                                        <?php foreach($data['mtns'] as $mtn) : ?>
                                            <option value="<?php echo $mtn->ID;?>" <?php selectdCheck($data['mtn'],$mtn->ID);?>><?php echo $mtn->Mtn;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['mtn_err'];?></span>
                                </div>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-sm table" id="receipts-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="d-none">Pid</th>
                                        <th width="50%">Product</th>
                                        <th>Transfered Qty</th>
                                        <th>Received Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['table'] as $table) : ?>
                                        <tr>
                                            <td class="d-none"><input type="text" name="booksid[]" value="<?php echo $table['pid'];?>"></td>
                                            <td><input type="text" class="table-input w-100" name="booksname[]" value="<?php echo $table['book'];?>" readonly></td>
                                            <td><input type="text" class="table-input" name="trqtys[]" value="<?php echo $table['trqty'];?>" readonly></td>
                                            <td><input type="text" class="table-input" name="qtys[]" value="<?php echo $table['qty'];?>"></td>
                                        </tr>
                                    <?php endforeach; ?> 
                                </tbody>
                            </table>
                        </div><!-- /.table-responsive -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <div class="d-grid d-md-block">
                    <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                    <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                    <input type="hidden" name="receipttype" value="<?php echo $data['type'];?>">
                    <button class="btn btn-primary" type="submit"> Save </button>
                </div>
            </form>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/add-receipts-inter.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    