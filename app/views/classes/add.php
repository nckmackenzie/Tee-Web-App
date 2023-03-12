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
                    <a href="<?php echo URLROOT;?>/classes" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
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
                    <form action="" id="addclass" autocomplete="off">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="bankname">Class Name</label>
                                <input type="text" class="form-control form-control-sm mandatory" 
                                       name="classname" id="classname"
                                       placeholder="Enter class name"
                                       value="<?php echo $data['classname'];?>">
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
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/classes/add.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    