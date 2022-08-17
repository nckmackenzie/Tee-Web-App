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
                <div class="card">
                    <div class="card-body">
                       <div class="row">
                            <div class="col-md-4 mb-2">
                                <label for="supplier">Supplier</label>
                                <select name="supplier" id="supplier" class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['supplier'],$data['supplier_err'],$data['touched']);?>">
                                    <option value="" selected disabled>Select supplier</option>
                                    <?php foreach($data['suppliers'] as $supplier) : ?>
                                        <option value="<?php echo $supplier->ID;?>" <?php selectdCheck($data['supplier'],$supplier->ID);?>><?php echo $supplier->SupplierName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['supplier_err'];?></span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="invoicedate">Invoice Date</label>
                                <input type="date" name="invoicedate" id="invoicedate" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['invoicedate'],$data['invoicedate_err'],$data['touched']);?>"
                                       value="<?php echo $data['invoicedate'];?>">
                                <span class="invalid-feedback"><?php echo $data['invoicedate_err'];?></span>
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="duedate">Due Date</label>
                                <input type="date" name="duedate" id="duedate" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['duedate'],$data['duedate_err'],$data['touched']);?>"
                                       value="<?php echo $data['duedate'];?>">
                                <span class="invalid-feedback"><?php echo $data['duedate_err'];?></span>
                            </div>
                            <div class="col-md-4">
                                <label for="vattype">Vat Type</label>
                                <select name="vattype" id="vattype" class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['vattype'],$data['vattype_err'],$data['touched']);?>">
                                    <option value="" selected disabled>Select vat type</option>
                                    <?php foreach($data['vattypes'] as $vattype) : ?>
                                        <option value="<?php echo $vattype->ID;?>" <?php selectdCheck($data['vattype'],$vattype->ID);?>><?php echo $vattype->VatType;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['vattype_err'];?></span>
                            </div>
                            <div class="col-md-4">
                                <label for="vat">Vat</label>
                                <select name="vat" id="vat" class="form-select form-select-sm mandatory 
                                        <?php echo inputvalidation($data['vat'],$data['vat_err'],$data['touched']);?>" disabled>
                                    <option value="" selected disabled>Select vat</option>
                                    <?php foreach($data['vats'] as $vat) : ?>
                                        <option value="<?php echo $vat->ID;?>" <?php selectdCheck($data['vat'],$vat->ID);?>><?php echo $vat->Vat;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['vat_err'];?></span>
                            </div>
                            <div class="col-md-4">
                                <label for="invoiceno">Invoice No</label>
                                <input type="text" name="invoiceno" id="invoiceno" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['invoiceno'],$data['invoiceno_err'],$data['touched']);?>"
                                       value="<?php echo $data['invoiceno'];?>"
                                       placeholder="eg 1001">
                                <span class="invalid-feedback"><?php echo $data['invoiceno_err'];?></span>
                            </div>
                       </div> 
                    </div>
                </div>
            </div>
            <!-- Products entry -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                       <div class="row">
                            <div class="col-md-6">
                                <label for="book">Product</label>
                                <select name="book" id="book" class="form-select form-select-sm">
                                    <option value="">Select product</option>
                                    <?php foreach($data['books'] as $book) : ?>
                                        <option value="<?php echo $book->ID;?>"><?php echo $book->BookName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback bookspan"></span>
                            </div>
                            <div class="col-md-2">
                                <label for="qty">Qty</label>
                                <input type="number" name="qty" id="qty" 
                                       class="form-control form-control-sm"
                                       placeholder="eg 1">
                                <span class="invalid-feedback qtyspan"></span>
                            </div>
                            <div class="col-md-2">
                                <label for="rate">Rate</label>
                                <input type="number" name="rate" id="rate" 
                                       class="form-control form-control-sm"
                                       readonly>
                                <span class="invalid-feedback ratespan"></span>
                            </div>
                            <div class="col-md-2">
                                <label for="gross">Gross</label>
                                <input type="number" name="gross" id="gross" 
                                       class="form-control form-control-sm"
                                       readonly>
                                <span class="invalid-feedback grossspan"></span>
                            </div>
                            <div class="col-md-1 mt-2">
                                <button type="button" class="btn btn-sm btn-success w-100 btnadd">Add</button>
                            </div>
                       </div> 
                    </div>
                </div>
            </div>
            <!-- Details table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                       <div class="row">
                          <div class="col-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered nowrap w-100 dt-responsive" id="detailstable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="d-none">BookId</th>
                                            <th width="50%">Product</th>
                                            <th>Qty</th>
                                            <th>Rate</th>
                                            <th>Gross</th>
                                            <th width="5%">Remove</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                          </div>              
                       </div> 
                    </div>
                </div>
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
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/invoices/calculations.js"></script>                   
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/invoices/add-invoice.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    