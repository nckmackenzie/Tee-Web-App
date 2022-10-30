<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Invoice Payment</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-12">
            <div class="card d-print-bg-white">
                <div class="card-body">
                    <div class="clearfix">
                        <div class="float-start mb-3">
                            <!-- LOGO HERE -->
                        </div>
                        <div class="float-end">
                            <h4 class="m-0 d-print-none">Invoice Payment</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="float-start mt-3">
                                <p><b>PCEA Theology Education By Extension</b></p>
                                <p class="font-13"><strong>Contact: </strong> 0797670845</p>
                                <p class="font-13"><strong>Email: </strong> accountant@pceatee.com</p>
                            </div>
                        </div>
                        <div class="col-sm-4 offset-sm-2">
                            <div class="mt-3 float-sm-end">
                                <p class="font-13"><strong>Payment To: </strong> <span class=""><?php echo $data['supplier'];?></span></p>
                                <p class="font-13"><strong>Payment Date: </strong> <?php echo $data['pdate'];?></p>
                                <p class="font-13"><strong>Payment ID: </strong> <span class=""><?php echo $data['payid'];?></span></p>
                            </div>
                        </div>
                    </div><!-- end row -->
                    <div class="row">
                        <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mt-4">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Invoice No</th>
                                            <th>Invoice Date</th>
                                            <th class="text-end">Invoice Value</th>
                                            <th class="text-end">Balance</th>
                                            <th class="text-end">Payment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['payments'] as $payment) : ?>
                                            <tr>
                                                <td><?php echo $payment->InvoiceNo;?></td>
                                                <td><?php echo date('d-m-Y',strtotime($payment->InvoiceDate));?></td>
                                                <td class="text-end"><?php echo number_format($payment->InvoiceValue,2);?></td>
                                                <td class="text-end"><?php echo number_format($payment->Balance,2);?></td>
                                                <td class="text-end"><?php echo number_format($payment->Payment,2);?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2">Total</th>
                                            <th class="text-end"><?php echo number_format($data['invoicevaluetotal'],2);?></th>
                                            <th class="text-end"><?php echo number_format($data['balancetotal'],2);?></th>
                                            <th class="text-end"><?php echo number_format($data['paymentstotal'],2);?></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div><!-- end row -->
                    <div class="d-print-none mt-4">
                        <div class="text-end">
                            <a href="javascript:window.print()" class="btn btn-primary"><i class="mdi mdi-printer"></i> Print</a>
                        </div>
                    </div> 
                </div><!--./card-body-->
            </div><!--./card-->
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    