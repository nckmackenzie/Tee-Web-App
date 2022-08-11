<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <div class="mt-1 alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Alert!</strong> You will be unable to undo this transaction after saving. Confirm details before saving.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/exams/createreceiptfromgroup" name="groupform" autocomplete="off" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="receiptdate">Receipt Date</label>
                                    <input type="date" name="receiptdate" id="receiptdate" 
                                           class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['receiptdate'],$data['receiptdate_err'],$data['touched']);?>"
                                           value="<?php echo $data['receiptdate'];?>">
                                    <span class="invalid-feedback"><?php echo $data['receiptdate_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="group">Group</label>
                                    <select name="group" id="group" class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['group'],$data['group_err'],$data['touched']);?>">
                                        <option value="">Select group received from</option>
                                        <?php foreach($data['groups'] as $group) : ?>
                                            <option value="<?php echo $group->ID;?>" <?php selectdCheck($data['group'],$group->ID);?>><?php echo $group->GroupName;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['group_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="exam">Exam</label>
                                    <select name="exam" id="exam" class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['exam'],$data['exam_err'],$data['touched']);?>">
                                        <option value="">Select exam</option>
                                        <?php foreach($data['exams'] as $exam) : ?>
                                            <option value="<?php echo $exam->ID;?>" <?php selectdCheck($data['exam'],$exam->ID);?>><?php echo $exam->ExamName;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['exam_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="submitdate">Submited For Marking Date</label>
                                    <input type="date" name="submitdate" id="submittdate" 
                                           class="form-control form-control-sm mandatory 
                                           <?php echo inputvalidation($data['submitdate'],$data['submitdate_err'],$data['touched']);?>"
                                           value="<?php echo $data['submitdate'];?>">
                                    <span class="invalid-feedback"><?php echo $data['submitdate_err'];?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="remarks">Remarks</label>
                                    <input type="text" id="remarks" name="remarks" 
                                           class="form-control form-control-sm"
                                           value="<?php echo $data['remarks'];?>">
                                </div>
                            </div>
                            <div class="col-9 mx-auto">
                                <div class="table-responsive">
                                    <table class="table table-sm w-100 dt-responsive nowrap" id="groupmembers">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="d-none">studentid</th>
                                                <th>Student Name</th>
                                                <th width="10%">Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['table'] as $table): ?>
                                                <tr>
                                                    <td class="d-none"><input type="text" name="studentsid[]" value="<?php echo $table['sid'];?>"></td>
                                                    <td><input type="text" class="table-input w-100" name="names[]" value="<?php echo $table['name'];?>" readonly></td>
                                                    <td>
                                                        <button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- /.row -->
                        <div class="d-grid d-md-block">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/exams/receipt-from-group.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    