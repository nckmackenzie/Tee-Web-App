<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">G/L Accounts</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('glaccount_msg','alert');?>
      
    <div class="row">
        <div class="col-12">
            <div class="card card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="<?php echo URLROOT;?>/glaccounts/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add G/L Account</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table dt-responsive table-centered w-100 nowrap" id="glaccounts-datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">ID</th>
                                <th>G/L Account</th>
                                <th>Account Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['glaccounts'] as $glaccount) : ?>
                                <tr>
                                    <td class="d-none"><?php echo $glaccount->ID;?></td>
                                    <td><?php echo $glaccount->AccountName;?></td>
                                    <td><?php echo $glaccount->AccountType;?></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> 
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/glaccounts/glaccounts.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    