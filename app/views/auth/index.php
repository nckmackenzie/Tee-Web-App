<?php require APPROOT .'/views/inc/layout/auth/header.php'; ?>

    <form action="#" autocomplete="off">
        <div class="mb-3">
            <label for="userid" class="form-label">User ID</label>
            <input class="form-control" name="email" type="email" id="userid" required placeholder="Enter your user id">
        </div>
        <div class="mb-3">
            <a href="./reset_password" class="text-muted float-end"><small>Forgot your password?</small></a>
            <label for="password" class="form-label">Password</label>
            <div class="input-group input-group-merge">
                <input type="password" name="password" class="form-control" placeholder="Enter your passwordid="password" class="form-control" placeholder="Enter your password">
                <div class="input-group-text" data-password="false">
                    <span class="password-eye"></span>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <label for="center" class="form-label">Center</label>
            <select class="form-select" name="center" type="email" id="center" required>
                <option value="1">Head Office</option>
                <option value="2">Kikuyu</option>
                <option value="3">Nakuru</option>
            </select>
        </div>
        <div class="d-grid d-md-block">
            <button class="btn btn-primary" type="submit"> Log In </button>
        </div>
    </form>

<?php require APPROOT .'/views/inc/layout/auth/footer.php'; ?>