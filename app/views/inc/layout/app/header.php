<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $data['title'];?> | P.C.E.A T.E.E</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <!-- App favicon -->
        <link rel="shortcut icon" href="<?php echo URLROOT;?>/img/logos/favicon.ico">
        <!-- Datatable styles -->
        <?php if(isset($data['has_datatable']) && $data['has_datatable'] === true) : ?>
            <link href="<?php echo URLROOT;?>/dist/css/vendor/dataTables.bootstrap5.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo URLROOT;?>/dist/css/vendor/responsive.bootstrap5.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo URLROOT;?>/dist/css/vendor/buttons.bootstrap5.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo URLROOT;?>/dist/css/vendor/select.bootstrap5.css" rel="stylesheet" type="text/css" />
        <?php endif; ?>    
        <!-- App css -->
        <link href="<?php echo URLROOT;?>/dist/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo URLROOT;?>/dist/css/app.min.css" rel="stylesheet" type="text/css" id="light-style" />
        <link href="<?php echo URLROOT;?>/dist/css/app-dark.min.css" rel="stylesheet" type="text/css" id="dark-style" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
        <link href="<?php echo URLROOT;?>/dist/css/style.css" rel="stylesheet" type="text/css" />
    </head>

    