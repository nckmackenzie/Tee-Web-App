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
                    <a href="<?php echo URLROOT;?>/invoices" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title -->
    <form action="<?php echo URLROOT;?>/invoices/createupdate" method="post" name="form" autocomplete="off"> 
        <div class="row">
            <div class="col-12">
                
            </div>
        </div><!-- /.row -->
        <div class="d-grid d-md-block">
            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
            <button type="submit" class="btn btn-sm btn-primary">Save</button>
        </div>
    </form>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    