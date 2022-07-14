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
        <?php if(!empty($data['error'])): ?>
            <div class="col-md-6 mx-auto">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Oops!</strong> <?php echo $data['error'];?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-12">
            <form action="<?php echo URLROOT;?>/stocks/createupdatetransfer" method="post" name="transferform" autocomplete="off">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label for="date" class="form-label">Transfer Date</label>
                                    <input type="date" name="date" id="date" 
                                          class="form-control form-control-sm mandatory 
                                          <?php echo inputvalidation($data['date'],$data['date_err'],$data['touched']);?>"
                                          value="<?php echo $data['date']; ?>">
                                    <span class="invalid-feedback"><?php echo $data['date_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label for="center" class="form-label">Transfer To</label>
                                    <select name="center" id="center" class="form-select form-select-sm 
                                            <?php echo inputvalidation($data['center'],$data['center_err'],$data['touched']);?>">
                                        <option value="" selected disabled>Select Center</option>
                                        <?php foreach($data['centers'] as $center) : ?>
                                            <option value="<?php echo $center->ID;?>" <?php selectdCheck($data['center'],$center->ID);?>><?php echo $center->CenterName;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['center_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-1">
                                    <label for="mtn" class="form-label">MTN No</label>
                                    <input type="text" name="mtn" id="mtn" 
                                           class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['mtn'],$data['mtn_err'],$data['touched']);?>" 
                                           value="<?php echo $data['mtn'];?>" required>
                                    <span class="invalid-feedback"><?php echo $data['mtn_err'];?></span>
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
                                            <td><input type="text" class="table-input" name="booksname[]" value="<?php echo $table['book'];?>"></td>
                                            <td><input type="text" class="table-input" name="qtys[]" value="<?php echo $table['qty'];?>"></td>
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
                    <button class="btn btn-primary login-btn" type="submit"> Save </button>
                </div>
            </form>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/add-transfers.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    