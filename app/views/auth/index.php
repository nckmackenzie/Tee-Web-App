<?php require APPROOT .'/views/inc/layout/auth/header.php'; ?>

    <form action="#" autocomplete="off" id="login-form">
        <div class="mb-3">
            <label for="userid" class="form-label">User ID</label>
            <input type="text" name="userid" id="userid" class="form-control 
                   <?php echo inputvalidation($data['userid'],$data['userid_err']); ?>" 
                   placeholder="Enter your user id" autocomplete="nope" required>
            <span class="invalid-feedback"><?php echo $data['userid_err'];?></span>
        </div>
        <div class="mb-3">
            <a href="<?php echo URLROOT;?>/auth/reset_password" class="text-muted float-end"><small>Forgot your password?</small></a>
            <label for="password" class="form-label">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" name="password" class="form-control 
                       <?php echo inputvalidation($data['password'],$data['password_err']); ?>" 
                       placeholder="Enter your password" required>
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
            <span class="invalid-feedback"><?php echo $data['password_err'];?></span>
        </div>
        <div class="mb-3">
            <label for="center" class="form-label">Center</label>
            <select class="form-select <?php echo inputvalidation($data['center'],$data['center_err']); ?>"
                    name="center" id="center" required>
                <option value="" selected disabled>Select Your Center</option>
                <?php foreach($data['centers'] as $center) : ?>
                    <option value="<?php echo $center->ID;?>" <?php selectdCheck($data['center'],$center->ID) ?>><?php echo $center->CenterName;?></option>
                <?php endforeach; ?>
            </select>
            <span class="invalid-feedback"><?php echo $data['center_err'];?></span>
        </div>
        <div class="d-grid d-md-block">
            <button class="btn btn-primary login-btn" type="submit"> Log In </button>
        </div>
    </form>
    

<?php require APPROOT .'/views/inc/layout/auth/footer.php'; ?>
<script type="module" src="<?php echo URLROOT;?>/dist/js/pages/login.js"></script>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>