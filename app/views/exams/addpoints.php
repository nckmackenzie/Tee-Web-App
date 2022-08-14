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
                    <a href="<?php echo URLROOT;?>/points" class="btn btn-sm btn-warning ms-2">&larr; Back</a>
                </h4>
            </div>
        </div>
    </div>     
    <!-- end page title --> 
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card">
                <div class="card-header"><?php echo $data['title'];?></div>
                <div class="card-body">
                    <form action="<?php echo URLROOT;?>/exams/createupdatepoints" method="post" name="form" autocomplete="off">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="course">Course</label>
                                    <select name="course" id="course" 
                                            class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['course'],$data['course_err'],$data['touched']);?>">
                                        <option value="" selected disabled>Select course</option>
                                        <?php foreach($data['courses'] as $course) : ?>
                                            <option value="<?php echo $course->ID;?>" <?php selectdCheck($data['course'],$course->ID);?>><?php echo $course->CourseName;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['course_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="book">Book</label>
                                    <select name="book" id="book" 
                                            class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['book'],$data['book_err'],$data['touched']);?>">
                                        <option value="" selected disabled>Select book</option>
                                        <?php if($data['touched'] && !empty($data['course'])) : ?>
                                            <?php foreach($data['books'] as $book) : ?>
                                                <option value="<?php echo $book->ID;?>" <?php selectdCheck($data['book'],$book->ID);?>><?php echo $book->BookName;?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['book_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="group">Group</label>
                                    <select name="group" id="group" 
                                            class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['group'],$data['group_err'],$data['touched']);?>">
                                        <option value="" selected disabled>Select group</option>
                                        <?php foreach($data['groups'] as $group) : ?>
                                            <option value="<?php echo $group->ID;?>" <?php selectdCheck($data['group'],$group->ID);?>><?php echo $group->GroupName;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['group_err'];?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label for="category">Category</label>
                                    <select name="category" id="category" 
                                            class="form-select form-select-sm mandatory 
                                            <?php echo inputvalidation($data['category'],$data['category_err'],$data['touched']);?>">
                                        <option value="" selected disabled>Select category</option>
                                        <?php foreach($data['categories'] as $category) : ?>
                                            <option value="<?php echo $category->ID;?>" <?php selectdCheck($data['category'],$category->ID);?>><?php echo $category->Category;?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="invalid-feedback"><?php echo $data['category_err'];?></span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="table-responsive">
                                    <table class="table table-sm w-100 nowrap dt-responsive" id="groupmembers">
                                        <thead class="table-light">
                                            <th class="d-none">Sid</th>
                                            <th>Student</th>
                                            <th>Points</th>
                                            <th>Remarks</th>
                                        </thead>
                                        <tbody>
                                            <?php foreach($data['table'] as $table): ?>
                                                <tr>
                                                    <td class="d-none"><input type="text" name="studentsid[]" value="<?php echo $table['sid'];?>"></td>
                                                    <td><input type="text" class="table-input w-100" name="names[]" value="<?php echo $table['name'];?>" readonly></td>
                                                    <td><input type="number" class="table-input w-100" name="points[]" value="<?php echo $table['point'];?>" readonly></td>
                                                    <td><input type="text" class="table-input w-100" name="remarks[]" value="<?php echo $table['remark'];?>" readonly></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="grid d-md-block">
                            <button type="submit" class="btn btn-sm btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>                    
</div> <!-- container -->
<?php require APPROOT .'/views/inc/layout/app/footer.php'; ?>  
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/exams/add-points.js"></script>                  
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>                    