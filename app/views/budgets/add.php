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
                    <a href="<?php echo URLROOT;?>/budgets" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-12">
            <?php if(!empty($data['save_err'])) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Oops!</strong> <?php echo $data['save_err'];?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-md-9 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/budgets/createupdate" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label for="budgetname">Budget Name</label>
                                <input type="text" name="budgetname" id="budgetname" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['budgetname'],$data['budgetname_err'],$data['touched']);?>"
                                       value="<?php echo $data['budgetname'];?>"
                                       placeholder="eg Budget 2021/2022">
                                <span class="invalid-feedback"><?php echo $data['budgetname_err'];?></span>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="budgetname">Budget Name</label>
                                <select name="year" id="year" 
                                        class="form-select form-select-sm mandatory
                                        <?php echo inputvalidation($data['year'],$data['year_err'],$data['touched']);?>">
                                    <option value="">Select year</option>
                                    <?php foreach($data['years'] as $year) : ?>
                                        <option value="<?php echo $year->ID;?>" <?php selectdCheck($data['year'],$year->ID);?>><?php echo $year->YearName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback"><?php echo $data['year_err'];?></span>
                            </div>
                            <div class="col-12">
                                <table class="table table-sm nowrap dt-responsive w-100">
                                    <thead>
                                        <tr>
                                            <th class="d-none">ID</th>
                                            <th>Expense Account</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($data['table'] as $table) :?>
                                            <tr>
                                                <td class="d-none"><input type="text" name="accountsid[]" value="<?php echo $table['aid'];?>" readonly></td>
                                                <td><input type="text" class="table-input w-100" name="accountsname[]" value="<?php echo $table['name'];?>" readonly></td>
                                                <td><input type="number" class="table-input" name="amounts[]" value="<?php echo $table['amount'];?>"></td>
                                            </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="d-grid d-md-block">
                            <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                            <input type="hidden" name="isedit" value="<?php echo $data['isedit'];?>">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                  
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>                    
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    