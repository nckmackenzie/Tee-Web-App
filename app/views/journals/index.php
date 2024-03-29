<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <div class="d-flex align-items-center justify-content-between gap-1 my-1">
                    <h4 class="page-title lh-1">Journal Entries</h4>
                    <div class="actions">
                        <button type="button" class="btn btn-sm btn-info" id="prevbtn" <?php echo $data['isfirst'] ? 'disabled' : '';?>>&larr; Prev</button>
                        <button type="button" class="btn btn-sm btn-info" id="nextbtn" disabled>&rarr; Next</button>
                        <button type="button" class="btn btn-sm btn-danger btndel" id="deletbtn"
                                data-id="" data-bs-toggle="modal" data-bs-target="#centermodal" disabled>Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>     
    <!-- end page title -->
    <?php DeleteModal(URLROOT .'/journals/delete','centermodal','Are you sure you want to delete this journal entry?','id');?> 
    <div class="row">
        <div class="col-12" id="alertBox">
            <?php if(!empty($data['save_err'])) : ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> <?php echo $data['save_err'];?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/journals/createupdate" name="form" id="journalform" method="post" autocomplete="off">
                        <div class="row">
                            <div class="col-md-2 mb-2">
                                <label for="journalno">Journal No</label>
                                <input type="text" name="journalno" id="journalno" value="<?php echo $data['journalno']; ?>"
                                    class="form-control form-control-sm"  
                                    readonly>
                                <input type="hidden" name="journalnohidden" id="journalnohidden" value="<?php echo $data['journalno']; ?>" >
                                <input type="hidden" name="firstjournalno" id="firstjournalno" value="<?php echo $data['firstjournalno']; ?>" >
                            </div>
                            <div class="col-md-3">
                                <label for="jdate">Journal Date</label>
                                <input type="date" name="jdate" id="jdate" 
                                       class="form-control form-control-sm mandatory 
                                       <?php echo inputvalidation($data['jdate'],$data['jdate_err'],$data['touched']);?>"
                                       value="<?php echo $data['jdate'];?>">
                                <span class="invalid-feedback" id="jdate_span"><?php echo $data['jdate_err']; ?></span>
                            </div>
                            <div class="col-md-7 mb-2">
                                <label for="description">Description</label>
                                <input type="text" id="description" name="description"
                                    class="form-control form-control-sm"  
                                    value="<?php echo $data['description']; ?>"
                                    placeholder="Enter description for journal..optional">    
                            </div>
                            <div class="col-md-4">
                                <label for="account">G/L Account</label>
                                <select name="account" id="account" class="form-select form-select-sm mandatory">
                                    <option value="" selected disabled>Select account</option>
                                    <?php foreach($data['glaccounts'] as $account) : ?>
                                        <option value="<?php echo $account->ID;?>"><?php echo $account->AccountName;?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="invalid-feedback" id="account_span"></span>
                            </div>
                            <div class="col-md-2">
                                <label for="type">Debit/Credit</label>
                                <select name="type" id="type" class="form-select form-select-sm mandatory">
                                    <option value="" selected disabled>Select debit/credit</option>
                                    <option value="debit">Debit</option>
                                    <option value="credit">Credit</option>
                                </select>
                                <span class="invalid-feedback" id="type_span"></span>
                            </div>
                            <div class="col-md-2">
                                <label for="amount">Amount</label>
                                <input type="number" name="amount" id="amount" 
                                       class="form-control form-control-sm mandatory"
                                       placeholder="eg 1,200">
                                <span class="invalid-feedback" id="amount_span"></span>
                            </div>
                            <div class="col-md-2">
                                <label for="debits">Total Debits</label>
                                <input type="text" id="debits" name="debitstotal"
                                    class="form-control form-control-sm"
                                    value="<?php echo $data['debitstotal'];?>"  
                                    readonly>    
                            </div>
                            <div class="col-md-2">
                                <label for="credits">Total Credits</label>
                                <input type="text" id="credits" name="creditstotal"
                                       class="form-control form-control-sm"
                                       value="<?php echo $data['creditstotal'];?>"  
                                       readonly>    
                            </div>
                            <div class="col-2 my-2">
                                <button type="button" class="btn btn-sm btn-success w-100" id="addbtn">Add</button>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered dt-responsive w-100" id="details">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="d-none">Aid</th>
                                                <th>G/L Account</th>
                                                <th width="15%">Debit/Credit</th>
                                                <th width="15%">Debit</th>
                                                <th width="15%">Credit</th>
                                                <th width="10%">Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($data['accounts'] as $account) : ?>
                                                <tr>
                                                    <td class="d-none"><input type="text" name="accountsid[]" value="<?php echo $account['aid'];?>" readonly></td>
                                                    <td><input type="text" class="table-input w-100" name="accountsname[]" value="<?php echo $account['name'];?>" readonly></td>
                                                    <td style="width:15%"><input type="text" class="table-input" name="types[]" value="<?php echo $account['type'];?>" readonly></td>
                                                    <td style="width:15%"><input type="text" class="table-input" name="debits[]" value="<?php echo $account['debit'];?>" readonly></td>
                                                    <td style="width:15%"><input type="text" class="table-input" name="credits[]" value="<?php echo $account['credit'];?>" readonly></td>
                                                    <td style="width:10%"><button type="button" class="action-icon btn btn-sm text-danger fs-5 btndel">Remove</button></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="d-grid d-md-block">
                                <input type="hidden" name="id" value="<?php echo $data['id'];?>">
                                <input type="hidden" name="isedit" id="isedit" value="<?php echo $data['isedit'];?>">
                                <input type="hidden" name="isfirst" value="<?php echo $data['isfirst'];?>">
                                <button class="btn btn-sm btn-primary" type="submit"> Save </button>            
                            </div>
                        </div><!-- /.row -->
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<?php flash('journal_flash_msg','toast');?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/journals/index.js"></script>                  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/journals/calculations.js"></script>                  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/journals/ajax-requests.js"></script>                  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/journals/navigation.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    