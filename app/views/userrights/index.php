<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box mt-2">
                <div id="alertBox"></div>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <form id="rights-form" action="<?php echo URLROOT;?>/userrights/createupdate" method="post" name="form" autocomplete="off">
        <div class="row">
            <div class="col-md-4 col-lg-6 mx-auto my-2">
                <div class="card">
                    <div class="card-header">
                        <div class="col-md-3 d-grid">
                            <button class="btn btn-primary btn-sm btn-block">Save</button> 
                        </div>
                    </div>
                    <div class="card-body">
                        <label for="user">Select user</label>
                        <select name="user" id="user" class="form-select form-select-sm">
                            <option value="" selected disabled>Select user</option>
                            <?php foreach($data['users'] as $user) : ?>
                                <option value="<?php echo $user->ID;?>" <?php selectdCheck($user->ID,$data['user']);?>><?php echo $user->UserName;?></option>
                            <?php endforeach ;?>
                        </select>
                        <span class="invalid-feedback"></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="spinner-container justify-content-center"></div>
        <div class="row">
            <div class="col-md-9 mx-auto rights-container">
                <div class="card d-none">
                    <div class="card-body">        
                        <table class="table table-sm dt-responsive w-100 nowrap" id="rights">
                            <thead>
                                <tr>
                                    <th class="d-none">Form ID</th>
                                    <th>Form Name</th>
                                    <th>Module</th>
                                    <th>Access</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>   
            </div><!-- /.col-md-9 -->
        </div>  
    </form>                 
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/rights/rights.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    