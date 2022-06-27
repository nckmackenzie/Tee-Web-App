<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $data['title'];?> | PCEA TEE</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="./img/logos/favicon.ico">
        <!-- Fonts -->
        <!-- <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;500;600;700;800&display=swap" rel="stylesheet"> -->

        <!-- App css -->
        <link href="<?php echo URLROOT; ?>/dist/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URLROOT; ?>/dist/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
        <link href="<?php echo URLROOT; ?>/dist/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
        <link rel="stylesheet" href="<?php echo URLROOT; ?>/dist/css/style.css">

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
                                    <h4 class="text-dark-50 text-center pb-0 fw-bold"><?php echo $data['title']; ?></h4>
                                </div>