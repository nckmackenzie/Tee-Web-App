<?php require APPROOT .'/views/inc/layout/auth/header.php'; ?>
      <div class="d-flex flex-column align-items-center">
            <h1 class="text-error mt-4">403</h1>
            <h4 class="text-uppercase text-danger mt-3">FORBIDDEN</h4>
            <p class="text-muted mt-3">Action Forbidden</p>
            <button class="btn btn-info mt-3" onclick="history.back()"><i class="mdi mdi-reply"></i> Go Back</button>
      </div>
<?php require APPROOT .'/views/inc/layout/auth/footer.php'; ?>
<?php require APPROOT .'/views/inc/layout/app/end.php'; ?>