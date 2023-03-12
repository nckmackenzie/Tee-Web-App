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
                    <a href="<?php echo URLROOT;?>/semisters" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
        <div class="col-12" id="alerBox">
            <?php if(!empty($data['has_error'])) : ?>
                <?php echo alert($data['error']); ?>
            <?php endif; ?>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="" method="post" id="semisterForm" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="semistername">Semister Name</label>
                                <input type="text" name="semistername" id="semistername" 
                                      class="form-control form-control-sm mandatory"
                                      value="<?php echo $data['semistername'];?>"
                                      placeholder="eg Semister 1 - 2022">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="class">Class</label>
                                <select name="class" id="class" class="form-select form-select-sm mandatory">
                                    <option value="" selected disabled>Select class</option>
                                    <?php foreach($data['classes'] as $class) : ?>
                                        <option value="<?php echo $class->ID;?>" <?php selectdCheck($data['class'],$class->ID)?>><?php echo $class->ClassName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="startdate">Start Date</label>
                                <input type="date" name="startdate" id="startdate" 
                                       class="form-control form-control-sm mandatory"
                                       value="<?php echo $data['startdate'];?>">
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="enddate">End Date</label>
                                <input type="date" name="enddate" id="enddate" 
                                       class="form-control form-control-sm mandatory"
                                       value="<?php echo $data['enddate'];?>">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>" id="isedit">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>" id="id">
                            <button type="submit" class="btn btn-sm btn-primary save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/semisters/addsemister.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    