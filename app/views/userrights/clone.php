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
    <div class="alertBox"></div>                    
    <div class="row">
        <div class="col-md-6 mt-5 mx-auto">
            <div class="col-12" id="alertBox"></div>
            <div class="card">
                <div class="card-header">Clone User Rights</div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/userrights/createclone" id="cloneForm" method="post">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="fromuser">User to clone from</label>
                                <select name="fromuser" id="fromuser" class="form-select form-select-sm mandatory">
                                    <option value="">Select user</option>
                                    <?php foreach($data['users'] as $user) : ?>
                                        <option value="<?php echo $user->ID;?>"><?php echo $user->UserName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="touser">User to clone to</label>
                                <select name="touser" id="touser" class="form-select form-select-sm mandatory">
                                    <option value="">Select user</option>
                                    <?php foreach($data['users'] as $user) : ?>
                                        <option value="<?php echo $user->ID;?>"><?php echo $user->UserName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <button type="submit" class="btn btn-sm btn-primary savebtn">Clone</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/rights/clone.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    