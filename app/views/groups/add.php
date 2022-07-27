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
                    <a href="<?php echo URLROOT;?>/groups" class="btn btn-sm btn-warning ms-1">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-6 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['isedit'] ? 'Edit Group' : 'Add Group'?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/groups/createupdate" method="post" autocomplete="off">
                        <div class="col-12 mb-3">
                            <label for="groupname" class="form-label">Group Name</label>
                            <input type="text" name="groupname" id="groupname" 
                                class="form-control form-control-sm mandatory 
                                <?php echo inputvalidation($data['groupname'],$data['groupname_err'],$data['touched']);?>"
                                value="<?php echo $data['groupname'];?>" placeholder="eg Amani">
                            <span class="invalid-feedback"><?php echo $data['groupname_err'];?></span>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="parishname" class="form-label">Parish Name</label>
                            <input type="text" name="parishname" id="parishname" 
                                class="form-control form-control-sm mandatory 
                                <?php echo inputvalidation($data['parishname'],$data['parishname_err'],$data['touched']);?>"
                                value="<?php echo $data['parishname'];?>" placeholder="eg PCEA Kalimoni">
                            <span class="invalid-feedback"><?php echo $data['parishname_err'];?></span>
                        </div>
                        <?php if($data['isedit']) : ?>
                            <div class="col-12">
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="active" class="form-check-input" id="active" <?php checkstate($data['active']);?>>
                                    <label class="form-check-label" for="active">Active</label>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="d-grid d-md-block">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>" >
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>" >
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    