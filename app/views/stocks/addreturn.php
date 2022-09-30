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
                    <a href="<?php echo URLROOT;?>/stocks/returns" class="btn btn-sm btn-warning ms-1">&larr; Back</a>
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
            <form action="<?php echo URLROOT;?>/stocks/createupdatereturn" autocomplete="off" method="post" name="returnForm" id="returnForm">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="returndate">Return Date</label>
                                <input type="date" name="returndate" id="returndate"
                                    class="form-control form-control-sm mandatory 
                                    <?php echo inputvalidation($data['returndate'],$data['returndate_err'],$data['touched']);?>"
                                    value="<?php echo $data['returndate'];?>">
                                <span class="invalid-feedback"><?php echo $data['returndate_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="from">Return From</label>
                                <input type="text" name="from" id="from"
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['from'],$data['from_err'],$data['touched']);?>"
                                       value="<?php echo $data['from'];?>"
                                       placeholder="enter name of returnee...">
                                <span class="invalid-feedback"><?php echo $data['from_err'];?></span>
                            </div>
                            <div class="col-12">
                                <label for="reason">Reason for return</label>
                                <input type="text" name="reason" id="reason"
                                    class="form-control form-control-sm mandatory 
                                    <?php echo inputvalidation($data['reason'],$data['reason_err'],$data['touched']);?>"
                                    value="<?php echo $data['reason'];?>"
                                    placeholder="reason for returning...">
                                <span class="invalid-feedback"><?php echo $data['reason_err'];?></span>
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="book">Book</label>
                                <select name="book" id="book" class="control form-select form-select-sm">
                                    <option value="">Select book</option>
                                    <?php foreach($data['books'] as $book) : ?>
                                        <option value="<?php echo $book->ID;?>"><?php echo $book->Title;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-2 mb-2">
                                <label for="qty">Qty</label>
                                <input type="number" name="qty" id="qty" class="control form-control form-control-sm" placeholder="eg 10">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-12">
                                <button type="button" class="btn btn-sm btn-success add">Add</button>
                            </div>
                        </div>
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table-sm table" id="returns-table">
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
                                            <td><input type="text" class="table-input w-100" name="booksname[]" value="<?php echo $table['book'];?>" readonly></td>
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
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/addReturn.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    