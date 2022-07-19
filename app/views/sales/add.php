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
                    <a href="<?php echo URLROOT;?>/sales" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-12" id="message"></div>
        <div class="col-12">
            <form action="<?php echo URLROOT;?>/sales/createupdate" method="post" autocomplete="off" name="salesform">
                <div class="card">
                    <div class="card-body">        
                        <div class="row">
                            <div class="col-md-4">
                                <!-- <div class="mb-3"> -->
                                    <label for="saleid" class="">Sale ID</label>
                                    <input type="text" name="saleid" id="saleid" 
                                           class="form-control form-control-sm" 
                                           value="<?php echo $data['salesid'];?>" readonly>
                                <!-- </div> -->
                            </div>
                            <div class="col-md-4">
                                <!-- <div class="mb-3"> -->
                                    <label for="sdate" class="">Sale Date</label>
                                    <input type="date" name="sdate" id="sdate" 
                                           class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['sdate'],$data['sdate_err'],$data['touched']);?>" 
                                           value="<?php echo $data['sdate'];?>" >
                                    <span class="invalid-feedback"><?php echo $data['sdate'];?></span>
                                <!-- </div> -->
                            </div>
                            <div class="col-md-4">
                                <!-- <div class="mb-3"> -->
                                    <label for="student" class="">Student</label>
                                    <select name="student" id="student" class="form-select form-select-sm 
                                            <?php echo inputvalidation($data['student'],$data['student_err'],$data['touched']);?>">
                                        <option value="" selected disabled>Select student</option>
                                        <?php foreach($data['students'] as $student) : ?>
                                            <option value="<?php echo $student->ID;?>" <?php selectdCheck($data['student'],$student->ID);?>><?php echo $student->StudentName;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['sdate'];?></span>
                                <!-- </div> -->
                            </div>
                        </div><!-- /.row -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="book">Book</label>
                                <select name="book" id="book" class="form-select form-select-sm">
                                    <option value="">Select book</option>
                                    <?php foreach($data['books'] as $book) :?>
                                        <option value="<?php echo $book->ID;?>"><?php echo $book->Title;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="stock" class="">Stock</label>
                                <input type="text" id="stock" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="rate" class="">Rate</label>
                                <input type="text" id="rate" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="qty" class="">Qty</label>
                                <input type="number" id="qty" class="form-control form-control-sm">
                            </div>
                            <div class="col-md-2">
                                <label for="value" class="">Value</label>
                                <input type="text" id="value" class="form-control form-control-sm" readonly>
                            </div> 
                            <div class="col-2 mt-1">
                                <button type="button" class="btn btn-sm btn-success w-100 btnadd">Add</button>
                            </div>                
                        </div><!-- /.row -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="table-responsive">
                                    <table class="table table-sm" id="addsale">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="d-none">BookId</th>
                                                <th width="30%">Book</th>
                                                <th>Rate</th>
                                                <th>Qty</th>
                                                <th>Value</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="row">
                            <div class="col-md-6"></div>
                            <div class="col-md-6">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="subtotal" class="form-label">Sub Total</label>
                                            <input type="text" name="subtotal" class="form-control form-control-sm" 
                                                   value="<?php echo $data['subtotal'];?>" readonly>    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="discount" class="form-label">Discount</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-sm" 
                                                       placeholder="eg 10 for 10%"
                                                       value="<?php echo $data['discount'];?>" 
                                                       aria-label="Discount" aria-describedby="basic-addon2">
                                                <span class="input-group-text input-padding" id="basic-addon2">%</span>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="net" class="form-label">Net Amount</label>
                                            <input type="text" name="net" class="form-control form-control-sm" 
                                                       value="<?php echo $data['net'];?>" readonly> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="paid" class="form-label">Amount Paid</label>
                                            <input type="number" name="paid" class="form-control form-control-sm 
                                                       <?php echo inputvalidation($data['paid'],$data['paid_err'],$data['touched']);?>" 
                                                       value="<?php echo $data['paid'];?>" > 
                                            <span class="invalid-feedback"><?php echo $data['paid_err'];?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="balance" class="form-label">Balance</label>
                                            <input type="text" name="balance" class="form-control form-control-sm" 
                                                       value="<?php echo $data['balance'];?>" readonly> 
                                        </div>
                                    </div>                
                                </div><!-- /.row -->
                            </div><!-- /.col-md-6 -->
                        </div><!-- /.row -->
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </div>
                </div>  
            </form>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/sales/add-sale.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    