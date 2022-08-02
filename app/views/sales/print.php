<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Sales Receipt</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-start mb-3">
                            <!-- LOGO HERE -->
                        </div>
                        <div class="float-end">
                            <h4 class="m-0 d-print-none">Receipt</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="float-start mt-3">
                                <p><b><?php echo $data['center'];?></b></p>
                                <p class="font-13"><strong>Contact: </strong> <?php echo $data['contact'];?></p>
                                <p class="font-13"><strong>Email: </strong> <?php echo $data['email'];?></p>
                            </div>
                        </div>
                        <div class="col-sm-4 offset-sm-2">
                            <div class="mt-3 float-sm-end">
                                <p class="font-13"><strong>Sale Date: </strong> &nbsp;&nbsp;&nbsp; <?php echo $data['sdate'];?></p>
                                <p class="font-13"><strong>Sale Status: </strong> <span class="badge bg-<?php echo $data['payclass'];?> float-end"><?php echo $data['paystatus'];?></span></p>
                                <p class="font-13"><strong>Sale ID: </strong> <span class="float-end">#<?php echo $data['saleid'];?></span></p>
                                <p class="font-13"><strong>Reference: </strong> <span class="float-end">#<?php echo $data['reference'];?></span></p>
                            </div>
                        </div>
                    </div><!-- end row -->
                </div><!--./card-body-->
            </div><!--./card-->
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    