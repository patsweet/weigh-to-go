<?php
    include("../app/app.php");
    if(isset($_SESSION['user'])) {
        $app->router->route('dashboard');
    }
    if ( isset($_POST['email']) && isset($_POST['password']) ) {
        $user = $app->getUserByEmail($_POST['email']);
        if ($user && $user->checkPassword($_POST['password'])) {
            if (!$user->active) {
                $app->sendActivationEmail($user->getEmail());
                $_SESSION['login_message'] = array("status"=>"danger", "message"=>"You haven't activated your account. Please check your e-mail for the activation link.");
            } else {
                $_SESSION['user'] = $_POST['email'];
                $app->router->route('dashboard');
            }
        } else {
            $_SESSION['login_message'] = array("status"=>"danger", "message"=>"Incorrect username/password.");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login | <?=App::CONTEST_NAME?></title>
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
        <link rel="stylesheet" href="<?=$app->router->asset('css/login.css');?>">
        <?php include_once('/gmti/www/wilmingt/www/common/genericphp/ody-adtech.php'); ?>
    </head>
    <body>
        <?php include_once("../common/gen-header.php"); ?>
        <div class="container main">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-lg-offset-1">
                    <?php if ($_SESSION['login_message']): ?>
                        <div class="alert alert-<?=$_SESSION['login_message']['status']?>">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <?=$_SESSION['login_message']['message']?>
                        </div>
                        <?php unset($_SESSION['login_message']); ?>
                    <?php endif ?>
                    <h2>Login</h2>
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                        <div class="form-group">
                            <label class="control-label" for="email">E-mail:</label>
                            <input name="email" class="form-control" type="email" placeholder="E-mail">
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="password">Password:</label>
                            <input name="password" class="form-control" type="password" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <button class="btn btn-default form-control" type="submit">Login</button>
                            <a class="btn btn-link form-control" href="<?=$app->router->absurl('forgot-password')?>">Forget password?</a>
                        </div>
                    </form>
                    <hr>
                    <h3 class="text-center">Don't have an account?</h3>
                    <p class="text-center">
                        <a class="btn btn-default" href="<?=$app->router->absurl("register")?>">Register</a>
                    </p>
                </div> <!-- END: mainbar -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-lg-offset-1">
                    <?php include_once("../common/sponsor_sidebar.php") ?>
                </div> <!-- END: sidebar -->
            </div> <!-- end row -->
        </div>
        <?php include("../common/gen-footer.php"); ?>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    </body>
</html>