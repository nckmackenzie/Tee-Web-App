<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Group Members</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php flash('groupmember_msg','alert');?>
    <?php DeleteModal(URLROOT .'/groups/deletemembers','centermodal','Delete All members for selected group?','id');?>
    <div class="row">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-centered dt-responsive w-100 nowrap" id="groupmembers-datatable">
                        <thead class="table-light">
                            <tr>
                                <th class="d-none">ID</th>
                                <th>Group Name</th>
                                <th>Parish Name</th>
                                <th>Members Count</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['groups'] as $group) : ?>
                                <tr>
                                    <td class="d-none"><?php echo $group->ID;?></td>
                                    <td><?php echo $group->GroupName;?></td>
                                    <td><?php echo $group->ParishName;?></td>
                                    <td><?php echo $group->MemberCount;?></td>
                                    <td>
                                        <a href="<?php echo URLROOT;?>/groups/manage/<?php echo $group->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-account-cog-outline"></i></a>
                                        <button class="action-icon btn text-danger btndel 
                                                <?php echo intval($group->MemberCount) === 0 ? 'd-none' : '';?>"
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
<?php flash('groupmember_flash_msg','toast');?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/groups/members.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    