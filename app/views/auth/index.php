<?php require APPROOT .'/views/inc/layout/auth/header.php'; ?>
    <?php if(ENV === 'development') : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        FOR <strong>TEST</strong> PURPOSES <strong>ONLY</strong>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
   <?php endif; ?>
    <form action="<?php echo URLROOT;?>/auth/login_act" autocomplete="off" id="login-form" method="POST">
        <div class="mb-3">
            <label for="contact" class="form-label">Contact</label>
            <input type="text" name="contact" id="contact" class="form-control 
                   <?php echo inputvalidation($data['contact'],$data['contact_err'],$data['touched']); ?>" 
                   placeholder="Enter your phone number" value="<?php echo $data['contact']; ?>" 
                   autocomplete="nope" maxlength="10" required>
            <span class="invalid-feedback"><?php echo $data['contact_err'];?></span>
        </div>
        <div class="mb-3">
            <a href="<?php echo URLROOT;?>/auth/reset_password" class="text-muted float-end"><small>Forgot your password?</small></a>
            <label for="password" class="form-label">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" name="password" class="form-control 
                       <?php echo inputvalidation($data['password'],$data['password_err'],$data['touched']); ?>" 
                       value="<?php echo $data['password']; ?>" placeholder="Enter your password" required>
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
            <span class="invalid-feedback" <?php echo (!empty($data['password_err'])) ? 'style="display:block;"' : ''; ?>><?php echo $data['password_err'];?></span>
        </div>
        <div class="mb-3">
            <label for="center" class="form-label">Center</label>
            <select class="form-select <?php echo inputvalidation($data['center'],$data['center_err'],$data['touched']); ?>"
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
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>