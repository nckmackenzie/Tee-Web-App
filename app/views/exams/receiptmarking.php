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
                <?php if(!$data['touched']) : ?>
                    <div class="mt-1 alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>Alert!</strong> You will be unable to undo this transaction after saving. Confirm details before saving.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <?php if(isset($data['save_err']) && !empty($data['save_err'])) : ?>
                    <div class="mt-1 alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> <?php echo $data['save_err'];?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/exams/createreceiptmarking" name="form" autocomplete="off" method="post">
                        <div class="d-grid d-md-block">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="receiptdate">Date Received</label>
                                        <input type="date" name="receiptdate" id="receiptdate" 
                                            class="form-control form-control-sm mandatory 
                                            <?php echo inputvalidation($data['receiptdate'],$data['receiptdate_err'],$data['touched']);?>"
                                            value="<?php echo $data['receiptdate'];?>">
                                        <span class="invalid-feedback"><?php echo $data['receiptdate_err'];?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label for="fromcenter">From Center</label>
                                        <select name="fromcenter" id="fromcenter" class="form-select form-select-sm mandatory">
                                            <option value="">Select center received from</option>
                                            <?php foreach($data['fromcenters'] as $center) : ?>
                                                <option value="<?php echo $center->ID;?>" <?php selectdCheck($data['fromcenter'],$center->ID);?>><?php echo $center->CenterName;?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="invalid-feedback"><?php echo $data['fromcenter_err'];?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="group">From Group</label>
                                        <select name="group" id="group" class="form-select form-select-sm mandatory">
                                            <option value="">Select group received from</option>
                                            <?php if($data['touched']) : ?>
                                                <?php foreach($data['groups'] as $group) : ?>
                                                    <option value="<?php echo $group->ID;?>" <?php selectdCheck($data['group'],$group->ID);?>><?php echo $group->GroupName;?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <span class="invalid-feedback"><?php echo $data['group_err'];?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="exam">Exam</label>
                                        <select name="exam" id="exam" class="form-select form-select-sm mandatory">
                                            <option value="">Select exam</option>
                                            <?php if($data['touched']) : ?>
                                                <?php foreach($data['exams'] as $exam) : ?>
                                                    <option value="<?php echo $exam->ID;?>" <?php selectdCheck($data['exam'],$exam->ID);?>><?php echo $exam->ExamName;?></option>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </select>
                                        <span class="invalid-feedback"><?php echo $data['exam_err'];?></span>
                                    </div>
                                </div>
                                <div class="col-12 mb-1">
                                    <div class="table-responsive">
                                        <table class="table table-sm dt-responsive nowrap w-100" id="groupmembers">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="d-none">studentid</th>
                                                    <th>Student Name</th>
                                                    <th>Marks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($data['table'] as $table): ?>
                                                    <tr>
                                                        <td class="d-none"><input type="text" name="studentsid[]" value="<?php echo $table['sid'];?>"></td>
                                                        <td><input type="text" class="table-input w-100" name="names[]" value="<?php echo $table['name'];?>" readonly></td>
                                                        <td><input type="number" class="table-input" name="marks[]" value="<?php echo $table['marks'];?>" ></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>            
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                        
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?> 
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/exams/receiptmarking.js"></script>                   
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    