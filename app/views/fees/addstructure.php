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
                    <a href="<?php echo URLROOT;?>/fees/structure" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
        <div class="col-12" id="alerBox"></div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-9 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="" method="post" id="structureForm" autocomplete="off">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="semister">Semister</label>
                                <select name="semister" id="semister" class="form-select form-select-sm mandatory">
                                    <option value="" selected disabled>Select semister</option>
                                    <?php foreach($data['semisters'] as $semister) : ?>
                                        <option value="<?php echo $semister->ID;?>"><?php echo $semister->SemisterName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"></span>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="amount">Total Fee Required</label>
                                <input type="number" name="amount" id="amount" 
                                       class="form-control form-control-sm mandatory"
                                       value="<?php echo $data['amount'];?>">
                                <span class="invalid-feedback"></span>
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" id="id" value="<?php echo $data['id'];?>">
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <button type="submit" class="btn btn-sm btn-primary save">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/fees/add-structure.js"></script>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    