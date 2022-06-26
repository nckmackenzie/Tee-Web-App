<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Log In | Hyper - Responsive Bootstrap 5 Admin Dashboard</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="./img/logos/favicon.ico">
        <!-- Fonts -->
        <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet"> -->

        <!-- App css -->
        <link href="./dist/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="./dist/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
        <link href="./dist/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />

    </head>

    <body class="loading authentication-bg" data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
        <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-lg-5">
                        <div class="card">
                            <!-- Logo -->
                            <div class="card-header pt-4 pb-4 text-center bg-primary d-flex gap-2 justify-content-center">
                                <img src="./img/logos/logo_24_white.png" alt="" height="24">
                                <span class="fs-4 text-white fw-bolder">PCEA T.E.E</span>
                            </div>

                            <div class="card-body p-4">
                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center pb-0 fw-bold">Sign In</h4>
                                </div>

                                <form action="#" autocomplete="off">
                                    <div class="mb-3">
                                        <label for="userid" class="form-label">User ID</label>
                                        <input class="form-control" name="email" type="email" id="userid" required placeholder="Enter your user id">
                                    </div>
                                    <div class="mb-3">
                                        <a href="./auth/forgot-password" class="text-muted float-end"><small>Forgot your password?</small></a>
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
                            </div> <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    </div> <!-- end col -->
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end page -->

        <footer class="footer footer-alt">
            2018 - 2021 © Hyper - Coderthemes.com
        </footer>

        <!-- bundle -->
        <script src="./dist/js/vendor.min.js"></script>
        <script src="./dist/js/app.min.js"></script>
        
    </body>
</html>
