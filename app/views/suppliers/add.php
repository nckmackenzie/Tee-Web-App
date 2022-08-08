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
                    <a href="<?php echo URLROOT;?>/suppliers" class="btn btn-warning btn-sm ms-1">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/suppliers/createupdate" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="suppliername" class="form-label">Supplier Name</label>
                                    <input type="text" name="suppliername" id="suppliername" 
                                           class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['suppliername'],$data['suppliername_err'],$data['touched']);?>"
                                           value="<?php echo $data['suppliername'];?>"
                                           placeholder="eg Test Supplies">  
                                    <span class="invalid-feedback"><?php echo $data['suppliername_err'];?></span>     
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contact" class="form-label">Supplier Contact</label>
                                    <input type="text" name="contact" id="contact" 
                                           class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['contact'],$data['contact_err'],$data['touched']);?>"
                                           value="<?php echo $data['contact'];?>"
                                           placeholder="eg 0700000000"
                                           maxlength="10">  
                                    <span class="invalid-feedback"><?php echo $data['contact_err'];?></span>     
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" name="address" id="address" 
                                           class="form-control form-control-sm"
                                           value="<?php echo $data['address'];?>"
                                           placeholder="eg P.O Box xxx-00100">  
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" 
                                           class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['email'],$data['email_err'],$data['touched']);?>"
                                           value="<?php echo $data['email'];?>"
                                           placeholder="eg test@example.com">  
                                    <span class="invalid-feedback"><?php echo $data['email_err'];?></span>     
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="pin" class="form-label">Tax PIN</label>
                                    <input type="text" name="pin" id="pin" 
                                           class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['pin'],$data['pin_err'],$data['touched']);?>"
                                           value="<?php echo $data['pin'];?>"
                                           placeholder="eg A123456789X"
                                           maxlength="11">  
                                    <span class="invalid-feedback"><?php echo $data['pin_err'];?></span>     
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="contactperson" class="form-label">Contact Person</label>
                                    <input type="text" name="contactperson" id="contactperson" 
                                           class="form-control form-control-sm"
                                           value="<?php echo $data['contactperson'];?>"
                                           placeholder="eg Jane Doe">  
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="openingbal" class="form-label">Opening Balance</label>
                                    <input type="number" name="openingbal" id="openingbal" 
                                           class="form-control form-control-sm"
                                           value="<?php echo $data['openingbal'];?>"
                                           placeholder="eg 25000">  
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="asof" class="form-label">As Of</label>
                                    <input type="date" name="asof" id="asof" 
                                           class="form-control form-control-sm 
                                           <?php echo inputvalidation($data['asof'],$data['asof_err'],$data['touched']);?>"
                                           value="<?php echo $data['asof'];?>">  
                                    <span class="invalid-feedback"><?php echo $data['asof_err'];?></span>     
                                </div>
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                        
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script>
    const tax = document.getElementById('pin');
    tax.addEventListener('keyup', function(e){
        const enteredValue = e.target.value;
        tax.value = enteredValue.toUpperCase();
    })
</script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    