<?php require APPROOT .'/views/inc/layout/auth/header.php'; ?>

    <form action="./auth/resetpassword">
        <div class="mb-3">
            <label for="contact" class="form-label">Phone No</label>
            <input class="form-control" type="text" id="contact"
                   name="contact"
                   value="<?php echo $data['contact']; ?>" 
                   placeholder="Enter your phone number"  required>
        </div>

        <div class="mb-0 text-center">
            <button class="btn btn-primary" type="submit">Reset Password</button>
        </div>
    </form>

<?php require APPROOT .'/views/inc/layout/auth/footer.php'; ?>