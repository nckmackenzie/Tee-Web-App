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
    <div class="row mt-1">
        <div class="col-12" id="message"></div>
    </div>
    <form action="<?php echo URLROOT;?>/sales/createupdate" method="post" autocomplete="off" name="salesform">
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <p class="m-0 align-self-center"><?php echo $data['title'];?></p>
                        <input type="hidden" name="id" id="id" value="<?php echo $data['id'];?>">
                        <input type="hidden" name="isedit" id="isedit" value="<?php echo $data['isedit'];?>">
                        <button type="submit" class="btn btn-sm btn-primary ml-auto w-25" id="submitBtn">Save</button>
                    </div>
                    <div class="card-body">        
                        <div class="row">
                            <div class="col-lg-2">
                                <div class="mb-3">
                                    <label for="saleid" class="">Sale ID</label>
                                    <input type="text" name="saleid" id="saleid" 
                                            class="form-control form-control-sm" 
                                            value="<?php echo $data['saleid'];?>" readonly>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="sdate" class="">Sale Date</label>
                                    <input type="date" name="sdate" id="sdate" 
                                            class="form-control form-control-sm mandatory
                                            <?php echo inputvalidation($data['sdate'],$data['sdate_err'],$data['touched']);?>" 
                                            value="<?php echo $data['sdate'];?>" >
                                    <span class="invalid-feedback"><?php echo $data['sdate_err'];?></span>
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="mb-3">
                                    <label for="pdate" class="">Payment Date</label>
                                    <input type="date" name="pdate" id="pdate" 
                                            class="form-control form-control-sm mandatory
                                            <?php echo inputvalidation($data['pdate'],$data['pdate_err'],$data['touched']);?>" 
                                            value="<?php echo $data['pdate'];?>" >
                                    <span class="invalid-feedback"><?php echo $data['pdate_err'];?></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="saletype">Sale Type</label>
                                    <select name="saletype" id="saletype" 
                                            class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['type'],$data['type_err'],$data['touched']);?>">
                                        <option value="" selected disabled>Sale to student or group</option>
                                        <option value="student" <?php selectdCheck($data['type'],'student');?>>Student</option>
                                        <option value="group" <?php selectdCheck($data['type'],'group');?>>Group</option>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['type_err'];?></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="studentorgroup" class="">Student/Group</label>
                                    <select name="studentorgroup" id="studentorgroup" 
                                            class="form-select form-select-sm mandatory select2 
                                            <?php echo inputvalidation($data['studentorgroup'],$data['studentgroup_err'],$data['touched']);?>"
                                            data-toggle="select2">
                                        <option value="" selected disabled>Select student or group</option>
                                        <?php if(($data['touched'] && !empty($data['type'])) || $data['isedit']) : ?>
                                            <?php foreach($data['studentsorgroups'] as $studentorgroup) : ?>
                                                <option value="<?php echo $studentorgroup->ID;?>" <?php selectdCheck($data['studentorgroup'],$studentorgroup->ID);?>><?php echo $studentorgroup->CriteriaName;?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['studentgroup_err'];?></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="paymethod">Pay method</label>
                                    <select name="paymethod" id="paymethod" class="form-select mandatory 
                                            <?php echo inputvalidation($data['paymethod'],$data['paymethod_err'],$data['touched']);?>">
                                        <option value="" selected disabled>Select payment method</option>
                                        <option value="1" <?php selectdCheck($data['paymethod'],1);?>>Cash</option>            
                                        <option value="2" <?php selectdCheck($data['paymethod'],2);?>>M-Pesa</option>            
                                        <option value="3" <?php selectdCheck($data['paymethod'],3);?>>Bank</option>            
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['paymethod_err'];?></span>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3">
                                    <label for="reference">Reference</label>
                                    <input type="text" name="reference" id="reference" 
                                            class="form-control mandatory 
                                            <?php echo inputvalidation($data['reference'],$data['reference_err'],$data['touched']);?>"
                                            value="<?php echo $data['reference'];?>"
                                            placeholder="eg MPESA Ref or Cheque No...">
                                    <span class="invalid-feedback"><?php echo $data['reference_err'];?></span>
                                </div>
                            </div>
                            <?php if($data['isedit']) : ?>
                                <div class="col-lg-12">
                                    <label for="reason">Reason for edit</label>
                                    <input type="text" name="reason" id="reason" 
                                           class="form-control form-control-sm mandatory"
                                            value="<?php echo $data['reason'];?>"
                                            placeholder="Enter reason for edit">
                                    <span class="invalid-feedback"></span>
                                </div>
                            <?php endif; ?>
                        </div><!-- /.row -->
                    </div><!-- /.card-body -->
                </div><!-- /.card -->
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4">
                                <label for="book">Book</label>
                                <select name="book" id="book" class="form-select form-select-sm">
                                    <option value="">Select book</option>
                                    <?php foreach($data['books'] as $book) :?>
                                        <option value="<?php echo $book->ID;?>"><?php echo $book->Title;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-lg-2">
                                <label for="stock" class="">Stock</label>
                                <input type="text" id="stock" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-lg-2">
                                <label for="rate" class="">Rate</label>
                                <input type="text" id="rate" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-lg-2">
                                <label for="qty" class="">Qty</label>
                                <input type="number" id="qty" class="form-control form-control-sm">
                            </div>
                            <div class="col-lg-2">
                                <label for="value" class="">Value</label>
                                <input type="text" id="value" class="form-control form-control-sm" readonly>
                            </div> 
                            <div class="col-lg-12">
                                <div class="form-check my-2">
                                    <input type="checkbox" name="softcopy" class="form-check-input" id="softcopy" >
                                    <label class="form-check-label" for="softcopy">Soft Copy</label>
                                </div>
                            </div> 
                            <div class="col-xs-4 col-lg-2 mt-1">
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
                                                <th width="40%">Book</th>
                                                <th>Rate</th>
                                                <th>Qty</th>
                                                <th>Value</th>
                                                <th class="d-none">SoftCopy</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['table'] as $table) : ?>
                                                <tr>
                                                    <td class="d-none"><input type="text" class="bid" name="booksid[]" value="<?php echo $table['bid'];?>" readonly></td>
                                                    <td><input type="text" class="table-input w-100 bname" name="booksname[]" value="<?php echo $table['bookname'];?>" readonly></td>
                                                    <td><input type="text" class="table-input rate" name="rates[]" value="<?php echo $table['rate'];?>" readonly></td>
                                                    <td><input type="text" class="table-input qty" name="qtys[]" value="<?php echo $table['qty'];?>" readonly></td>
                                                    <td><input type="text" class="table-input value" name="values[]" value="<?php echo $table['values'];?>" readonly></td>
                                                    <td class="d-none"><input type="text" class="table-input softcopy" name="softcopies[]" value="<?php echo $table['softcopy'];?>" readonly></td>
                                                    <td><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
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
                                            <input type="text" name="subtotal"id="subtotal" class="form-control form-control-sm" 
                                                    value="<?php echo $data['subtotal'];?>" readonly>    
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="discount" class="form-label">Discount</label>
                                            <div class="input-group">
                                                <input type="number" class="form-control form-control-sm" 
                                                        id="discount" name="discount"
                                                        placeholder="eg 10 for 10%"
                                                        value="<?php echo $data['discount'];?>" 
                                                        aria-label="Discount" aria-describedby="basic-addon2">
                                                <span class="input-group-text input-padding" id="basic-addon2">%</span>
                                            </div> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="deliveryfee" class="form-label">Delivery Fees</label>
                                            <input type="text" name="deliveryfee" id="deliveryfee" class="form-control form-control-sm" 
                                                   value="<?php echo $data['deliveryfee'];?>"> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="net" class="form-label">Net Amount</label>
                                            <input type="text" name="net" id="net" class="form-control form-control-sm" 
                                                        value="<?php echo $data['net'];?>" readonly> 
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="paid" class="form-label">Amount Paid</label>
                                            <input type="number" name="paid" id="paid" class="form-control form-control-sm mandatory
                                                        <?php echo inputvalidation($data['paid'],$data['paid_err'],$data['touched']);?>" 
                                                        value="<?php echo $data['paid'];?>" > 
                                            <span class="invalid-feedback"><?php echo $data['paid_err'];?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="balance" class="form-label">Balance</label>
                                            <input type="text" name="balance" id="balance" class="form-control form-control-sm" 
                                                        value="<?php echo $data['balance'];?>" readonly> 
                                        </div>
                                    </div>                
                                </div><!-- /.row -->
                            </div><!-- /.col-md-6 -->
                        </div><!-- /.row -->
                    </div>
                </div>  
            </div><!--/.col-lg-8 -->
            <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Group Members</span>
                    <button type="button" id="selectAll" class="btn btn-info btn-sm" disabled>Select all</button>
                </div>
                <div class="card-body p-0" id="studentList">
                    <?php foreach($data['students'] as $student) : ?>
                        <div class="table-like">
                            <div class="form-check">
                                <input type="checkbox" name="active[]" 
                                       class="form-check-input stdcheck" <?php echo converttobool($student['paid']) ? 'checked' : '' ;?>>
                            </div>
                            <div class="d-none"><input type="number" name="studentsid[]" value="<?php echo $student['sid'];?>" /></div>
                            <div class="studentname"><?php echo $student['studentname'];?></div>                        
                            <div class="contact"><?php echo $student['contact'];?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            </div><!--/.col-lg-8 -->
        </div><!--/.row-->
    </form>
</div> <!-- container -->

<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/sales/calculations.js"></script>                  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/sales/add-sale-v1.js"></script>                  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/sales/studentGroupHandler-v1.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    