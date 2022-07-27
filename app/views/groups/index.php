<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Groups</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('group_msg','alert');?>
    <?php DeleteModal(URLROOT .'/groups/delete','centermodal','Are you sure you want to delete this group?','id'); ?>

    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="<?php echo URLROOT;?>/groups/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Group</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered dt-responsive w-100 nowrap" id="groups-datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Group Name</th>
                                <th>Parish Name</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['groups'] as $group): ?>
                                <tr>
                                    <td class="d-none"><?php echo $group->ID;?></td>
                                    <td><?php echo strtoupper($group->GroupName);?></td>
                                    <td><?php echo strtoupper($group->ParishName);?></td>
                                    <td><span class="badge <?php echo badgeclasses($group->Status);?>"><?php echo $group->Status;?></span></td>
                                    <td>
                                        <a href="<?php echo URLROOT;?>/groups/edit/<?php echo $group->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                        <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $group->ID;?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#centermodal"
                                                    ><i class="mdi mdi-delete"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<?php flash('group_flash_msg','toast');?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/groups/groups.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    