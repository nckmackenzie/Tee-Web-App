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
                    <a href="<?php echo URLROOT;?>/groups/members" class="btn btn-sm btn-warning ms-1">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="alert-box"></div>
        <div class="col-md-8 mx-auto">
            <div class="card">  
                <div class="card-header">Manage Group Members</div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/groups/managecreateupdate" method="post" autocomplete="off" name="studentsform">
                        <div class="row">
                            <div class="col-md-6 mb-1">
                                <label for="group">Group</label>
                                <select name="group" id="group" class="form-select form-select-sm" disabled>
                                    <option value="">Select group</option>
                                    <?php foreach($data['groups'] as $group) : ?>
                                        <option value="<?php echo $group->ID;?>" <?php selectdCheck($data['group'],$group->ID);?>><?php echo $group->GroupName;?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-1">
                                <label for="student">Student</label>
                                <select name="student" id="student" class="form-select form-select-sm mandatory">
                                    <option value="">Select student</option>
                                    <?php foreach($data['students'] as $student) : ?>
                                        <option value="<?php echo $student->ID;?>"><?php echo $student->StudentName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12 col-lg-3">
                                <button type="button" class="btn btn-sm btn-success w-100 btnadd">Add</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-sm dt-responsive w-100 nowrap" id="members">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="d-none">ID</th>
                                                <th width="80%">Student</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['groupmembers'] as $member) : ?>
                                                <tr>
                                                    <td class="d-none"><input type="text" name="studentsid[]" value="<?php echo $member['sid'];?>"></td>
                                                    <td><input type="text" class="table-input" name="studentsname[]" value="<?php echo $member['studentname'];?>" readonly></td>
                                                    <td><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
                                                </tr>
                                            <?php endforeach;?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                            <input type="hidden" name="group" value="<?php echo $data['group'];?>">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/groups/manage.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    