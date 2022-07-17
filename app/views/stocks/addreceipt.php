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
                    <a href="<?php echo URLROOT;?>/stocks/receipts" class="btn btn-sm ms-2 btn-warning">&larr; Back</a>
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
                            <div class="col-12">
                                <div class="mb-3">
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="grn" name="receipttype" class="form-check-input" 
                                               value="grn" <?php echo $data['type'] === 'grn' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="grn">GRN</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input type="radio" id="internal" name="receipttype" 
                                               class="form-check-input" 
                                               value="internal" <?php echo $data['type'] === 'internal' ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="internal">Inter Transfer</label>
                                    </div>
                                </div>
                            </div>
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
                                    <label for="mtn" class="form-label">Transfer Note</label>
                                    <select name="mtn" id="mtn" class="form-select form-select-sm
                                    <?php echo inputvalidation($data['mtn'],$data['mtn_err'],$data['touched']);?>" <?php echo $data['type'] === 'grn' ? 'disabled' : ''; ?>>
                                        <option value="" selected disabled>Select Transfer Note</option>
                                        <?php foreach($data['mtns'] as $mtn) : ?>
                                            <option value="<?php echo $mtn->ID;?>"><?php echo $mtn->Mtn;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['mtn_err'];?></span>
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
                            
                        </div><!-- /.row -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <div class="card">
                    <div class="card-header">        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="bookid" class="form-label">Book</label>
                                    <select name="bookid" id="bookid" class="form-select form-select-sm">
                                        <option value="" selected disabled>Select Book</option>
                                        <?php foreach($data['books'] as $book) : ?>
                                            <option value="<?php echo $book->ID;?>"><?php echo $book->Title;?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-2">
                                    <label for="qty" class="form-label">Qty</label>
                                    <input type="number" name="qty" id="qty" class="form-control form-control-sm">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label for="value" class="form-label">Value</label>
                                    <input type="text" name="value" id="value" class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-sm btn-success btnadd" id="add">Add</button>
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
                                        <th>Product</th>
                                        <th>Qty</th>
                                        <th width="10%">Remove</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($data['table'] as $table) : ?>
                                        <tr>
                                            <td class="d-none"><input type="text" name="booksid[]" value="<?php echo $table['pid'];?>"></td>
                                            <td><input type="text" class="table-input" name="booksname[]" value="<?php echo $table['book'];?>" readonly></td>
                                            <td><input type="text" class="table-input" name="qtys[]" value="<?php echo $table['qty'];?>" readonly></td>
                                            <td>
                                                <button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button>
                                            </td>
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
                    <button class="btn btn-primary" type="submit"> Save </button>
                </div>
            </form>
                
            
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/add-receipts.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    