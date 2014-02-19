<?php
    include("../app/app.php");
    if(isset($_SESSION['user'])) {
        $user = $app->getUserByEmail($_SESSION['user']);
    } elseif (isset($_SESSION['emailpass'])) {
        $temp_user = $app->getUserByEmail($_SESSION['emailpass']);
    } elseif ($_GET['q']) {
        $linkid = base64_decode($_GET['q']);
        $stuff = explode("|weigh|to|go|", $linkid);
        $email = $stuff[1];
        if ( $temp_user = $app->getUserByEmail($email) ) {
            $_SESSION['emailpass'] = $email;
        }
    } else {
        $app->router->route("login");
    }
    if (!$user && !$temp_user) {
        die("error processing your request");
    }

    if ($_POST) {
        if ($_POST['password'] != $_POST['password2']) {
            $app->setError("Your passwords do not match.");
        } else {
            $temp_user->setPassword($_POST['password']);
            unset($_SESSION['emailpass']);
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?=App::CONTEST_NAME?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="Excercise, lose weight and get healthy with friends.">
        <meta name="apple-mobile-web-app-title" content="<?=App::CONTEST_NAME?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <!-- <link rel="apple-touch-icon" href="<?= $app->router->asset('images/apple-icon.png')?>"> -->
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?= $app->router->asset('css/base.css')?>">
        <link rel="stylesheet" href="<?= $app->router->asset('css/register.css')?>">
        <?php include_once('/gmti/www/wilmingt/www/common/genericphp/ody-adtech.php'); ?>
    </head>
    <body>
        <?php include_once("../common/gen-header.php"); ?>
        <div class="container main">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-lg-offset-1">
                    <?php if (!$_POST): ?>
                    <h2>Reset Password</h2>
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                        <div class="form-group">
                            <label class="control-label" for="password">Password:</label>
                            <input class="form-control" name="password" type="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="password2">Confirm password:</label>
                            <input class="form-control" name="password2" type="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <input class="btn btn-default form-control" type="submit" value="Submit">
                            <input type="hidden" name="email" value="<?=$email?>">
                        </div>
                    </form>
                    <?php else: ?>
                        <h3>Password successfully changed.</h3>
                        <?php if (isset($_SESSION['user'])): ?>
                            <p><a class="btn btn-default" href="<?= $app->router->absurl('dashboard') ?>">Return to dashboard</a></p>
                        <?php else: ?>
                            <p><a class="btn btn-default" href="<?= $app->router->absurl('login') ?>">Login</a></p>
                        <?php endif ?>
                    <?php endif ?>
                    <hr>
                    <?php if (!isset($_SESSION['user'])): ?>
                    <h3 class="text-center">Don't have an account?</h3>
                    <p class="text-center">
                        <a class="btn btn-default" href="<?= $app->router->absurl('register') ?>">Register</a>
                    </p>
                    <?php endif ?>
                </div> <!-- END: mainbar -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-lg-offset-1">
                    <?php include_once("../common/sponsor_sidebar.php") ?>
                </div> <!-- END: sidebar -->
            </div> <!-- END: row -->
        </div>
        <?php include("../common/gen-footer.php"); ?>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js" type="text/javascript"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?=$app->router->asset('js/register.js')?>"></script>
    </body>
</html>