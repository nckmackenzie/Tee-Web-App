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
                    <a href="<?php echo URLROOT;?>/prices" class="btn btn-warning btn-sm ms-1">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['isedit'] ? 'Edit' : 'Add' ;?> Price</div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/prices/createupdate" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bookid" class="form-label">Book</label>
                                    <select name="bookid" id="bookid" 
                                            class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['bookid'],$data['bookid_err'],$data['touched']); ?>">
                                        <option value="" selected disabled>Select Book</option>
                                        <?php foreach($data['books'] as $book) : ?>
                                            <option value="<?php echo $book->ID;?>" <?php selectdCheck($data['bookid'],$book->ID);?>><?php echo $book->Title;?></option>
                                        <?php endforeach;?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['bookid_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" name="price" id="price" 
                                           class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['price'],$data['price_err'],$data['touched']);?>"
                                           value="<?php echo $data['price']; ?>" placeholder="eg 800" required>
                                    <span class="invalid-feedback"><?php echo $data['price_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="startdate" class="form-label">From</label>
                                    <input type="date" name="startdate" id="startdate" 
                                           class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['startdate'],$data['startdate_err'],$data['touched']);?>"
                                           value="<?php echo $data['startdate'];?>" required>
                                    <span class="invalid-feedback"><?php echo $data['startdate_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="enddate" class="form-label">To</label>
                                    <input type="date" name="enddate" id="enddate" 
                                           class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['enddate'],$data['enddate_err'],$data['touched']);?>"
                                           value="<?php echo $data['enddate'];?>" required>
                                    <span class="invalid-feedback"><?php echo $data['enddate_err'];?></span>
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <button class="btn btn-primary" type="submit"> Save </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    