<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Financial Years</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <?php DeleteModal(URLROOT.'/years/delete','centermodal','Are your you want to delete this year?','id'); ?>     
    <?php DeleteModal(URLROOT.'/years/close','closemodal','Are your you want to close this year?','cid'); ?>     
    <?php DeleteModal(URLROOT.'/years/open','openmodal','Are your you want to reopen this year?','oid'); ?>     
    <?php flash('year_msg','alert'); ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-sm-4">
                            <a href="<?php echo URLROOT;?>/years/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Year</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-centered w-100 dt-responsive nowrap" id="years-datatable">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Year Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($data['years'] as $year) : ?>
                                    <tr>
                                        <td><?php echo $year->ID;?></td>
                                        <td><?php echo $year->YearName;?></td>
                                        <td><?php echo $year->StartDate;?></td>
                                        <td><?php echo $year->EndDate;?></td>
                                        <td><span class="badge <?php echo $year->Status === 'Open' ? 'bg-success' : 'bg-danger';?>"><?php echo $year->Status;?></span></td>
                                        <td>
                                            
                                            <?php if($year->Status === 'Open') : ?>
                                                <a href="<?php echo URLROOT;?>/years/edit/<?php echo $year->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                                <button class="action-icon btn text-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#closemodal"
                                                        onclick="rowFunction(this,'cid')"><i class="mdi mdi-cancel"></i></button>
                                                <button class="action-icon btn text-danger btndel" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#centermodal"
                                                        onclick="rowFunction(this,'id')"><i class="mdi mdi-delete"></i></button>
                                            <?php else: ?>
                                                <button class="action-icon btn text-warning" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#openmodal"
                                                        onclick="rowFunction(this,'oid')"><i class="mdi mdi-undo-variant"></i></button>
                                            <?php endif; ?>
                                        </td>
                                    </tr> 
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div><!-- end card body -->
            </div><!-- end car -->
        </div><!-- end col -->
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<?php flash('year_toast_msg','toast'); ?>
<script src="<?php echo URLROOT;?>/dist/js/pages/years.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    