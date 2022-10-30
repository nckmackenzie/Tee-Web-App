<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box mt-1" id='alerBox'></div>
        </div>
    </div>     
    <!-- end page title --> 
    <form action="" id="clear-form" autocomplete="off">
        <div class="row">
            <div class="col-md-3">
                <label for="type">Bankings/Mpesa</label>
                <select name="type" id="type" class="form-select form-select-sm mandatory">
                    <option value=""selected disabled>Select type</option>
                    <option value="bankings">Bankings</option>
                    <option value="mpesa">M-Pesa</option>
                </select>
                <span class="invalid-feedback"></span>
            </div>
            <div class="col-md-3">
                <label for="sdate">From</label>
                <input type="date" name="sdate" id="sdate" class="form-control form-control-sm mandatory">
                <span class="invalid-feedback"></span>
            </div>
            <div class="col-md-3">
                <label for="edate">To</label>
                <input type="date" name="edate" id="edate" class="form-control form-control-sm mandatory">
                <span class="invalid-feedback"></span>
            </div>
            <div class="col-md-2 align-self-end d-grid">
                <button type="button" class="btn btn-sm btn-success preview">Preview</button>
            </div>
        </div>
        <div class="spinner-container mt-2 justify-content-center"></div>
        <div id="bankings" class="mt-2 d-none">
            <div class="d-grid d-md-block">
                <button class="btn btn-sm btn-primary save">Save</button>
            </div>
            <div class="row mt-2">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-sm dt-responsive nowrap w-100" id="table">
                            <thead class="table-light">
                                <tr>
                                    <th class="d-none">ID</th>
                                    <th>Select</th>
                                    <th>Clear Date</th>
                                    <th>Transaction Date</th>
                                    <th>Amount</th>
                                    <th>Reference</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </form>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/bankings/clear.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    