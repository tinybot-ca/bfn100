<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/public.css'); ?>" />
    <!-- JavaScript -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script
            src="http://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <!-- Page Title -->
    <title>BFN100<?php if (isset($page_title)) { echo ' - ' . h($page_title); } ?><?php if(isset($preview) && $preview) { echo ' [PREVIEW]'; } ?></title>
</head>

<body>

<header>
    <nav class="navbar bg-dark navbar-dark navbar-expand-sm">
        <div class="container">
            <a class="navbar-brand" href="<?php echo url_for('/'); ?>">BFN100</a>
            <button class="navbar-toggler" type="button"
                data-toggle="collapse"
                data-target="#myTogglerNav"
                aria-controls="myTogglerNav"
                aria-expanded="false"
                aria-label="Toggle Navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="myTogglerNav">
                <div class="navbar-nav ml-auto">
                    <a class="nav-item nav-link <?php if(is_logged_in()) {echo 'd-none'; } ?>" href="<?php echo url_for('/login.php'); ?>">Login</a>
                    <a class="nav-item nav-link" href="<?php echo url_for('/pushups/new.php'); ?>">Submit</a>
                    <a class="nav-item nav-link" href="<?php echo url_for('/users/'); ?>">Users</a>
                    <a class="nav-item nav-link <?php if(!is_logged_in()) {echo 'd-none'; } ?>" href="<?php echo url_for('/logout.php'); ?>">Logout</a>
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="container mt-3">
    <?php echo display_session_message(); ?>
</div>