<?php require APPROOT .'/views/inc/layout/app/header.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/sidebar.php'; ?>
<!-- Start Content-->
<div class="container-fluid">
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right"></div>
                <h4 class="page-title">Books List</h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
 <?php DeleteModal(URLROOT .'/books/delete','centermodal','Are your you want to delete this book?','id'); ?>
 <?php flash('book_msg','alert'); ?> 

 <div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4">
                        <a href="<?php echo URLROOT;?>/books/add" class="btn btn-success mb-2"><i class="mdi mdi-plus-circle me-2"></i> Add Book</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-centered w-100 dt-responsive nowrap" id="books-datatable">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Book Code</th>
                                <th>Author</th>
                                <th>Current Stock</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <?php foreach($data['books'] as $book): ?>
                            <tr>
                                <td><?php echo $book->ID;?></td>
                                <td><?php echo $book->Title;?></td>
                                <td><?php echo $book->BookCode;?></td>
                                <td><?php echo $book->Author;?></td>
                                <td><?php echo $book->Stock;?></td>
                                <td><span class="badge <?php echo $book->Status === 'Active' ? 'bg-success' : 'bg-danger';?>"><?php echo $book->Status;?></span></td>
                                <td>
                                    <a href="<?php echo URLROOT;?>/books/edit/<?php echo $book->ID;?>" class="action-icon btn text-success"> <i class="mdi mdi-square-edit-outline"></i></a>
                                    <button class="action-icon btn text-danger btndel"
                                                    data-id="<?php echo $book->ID;?>" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#centermodal"
                                                    ><i class="mdi mdi-delete"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>    
                    </table>
                </div>
            </div><!-- /.card-body -->
        </div><!-- /.card -->
    </div><!-- /.col-12 -->
 </div><!-- /.row -->
 
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>
<?php flash('book_flash_msg','toast'); ?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/books.js"></script> 
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    