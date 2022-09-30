<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">User rights</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <form id="rights-form" action="<?php echo URLROOT;?>/userrights/createupdate" method="post" name="form" autocomplete="off">
        <div class="row">
            <div class="col-md-3 mb-1">
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
        <div class="row">
            <div class="col-md-2 mb-2">
                <button class="btn btn-primary btn-sm">Save</button> 
            </div>
        </div>
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body">        
                        <table class="table table-sm dt-responsive w-100 nowrap" id="rights">
                            <thead>
                                <tr>
                                    <th class="d-none">Form ID</th>
                                    <th>Form Name</th>
                                    <th>Access</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['forms'] as $form): ?>
                                    <tr>
                                        <td class="d-none"><input type="text" name="formsid[]" value="<?php echo $form->ID;?>"></td>
                                        <td><input type="text" class="table-input w-100 bg-transparent" name="formsname[]" value="<?php echo $form->FormName;?>" readonly></td>
                                        <td>
                                            <select style="width: 50%;" name="access[]" class="form-select form-select-sm">
                                                <option value="0">False</option>
                                                <option value="1">True</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>   
            </div><!-- /.col-md-9 -->
        </div>  
    </form>                 
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script src="<?php echo URLROOT;?>/dist/js/pages/rights/rights.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    