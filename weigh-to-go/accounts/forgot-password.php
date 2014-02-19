<?php
    include('../app/app.php');
    if(isset($_SESSION['user'])) {
        $app->router->route('dashboard');
    }
    if ( isset($_POST['email']) ) {
        $temp_user = $app->getUserByEmail($_POST['email']);
        if ( $temp_user ) {
            $email = $_POST['email'];
            // $linkid = base64_encode($user->id)."|weigh|to|go|" . $email;
            $activate_link = $app->router->absurl('password-reset') . "?q=" . $app->encode($email);

            $message = "Password Reset\n\nPlease click the link below to reset your password.\n\n".$activate_link."\n\nIf clicking the link does not work, you might have to copy and paste it into your browser.\n\nIf you no longer want to reset your password, disregard this email.";

            mail($email, App::CONTEST_NAME.": Password Reset", $message, "From: Keep The Beat <".App::EMAIL_FROM.">\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=utf-8");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Change Password | <?=App::CONTEST_NAME?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="Excercise, lose weight and get healthy with friends.">
        <meta name="apple-mobile-web-app-title" content="<?=App::CONTEST_NAME?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="apple-touch-icon" href="<?= $app->router->asset('images/apple-icon.png')?>">
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
                    <?php if (!$temp_user || !isset($temp_user) ): ?>
                    <h2>Request password change</h2>
                        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                            <?php if ( $_POST['email'] ): ?>
                                <div class="alert alert-danger">
                                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                                    <strong>User with that e-mail does not exist.</strong>
                                </div>
                            <?php endif ?>
                            <div class="form-group">
                                <label class="control-label" for="email">E-mail:</label>
                                <input name="email" class="form-control" type="email" placeholder="E-mail">
                            </div>
                            <div class="form-group">
                                <input class="btn btn-default form-control" type="submit" value="Submit">
                            </div>
                        </form>
                    <?php else: ?>
                        <h3>Password change request sent</h3>
                        <p>Check your inbox for an e-mail from <?=App::EMAIL_FROM?> with directions for resetting your password.</p>
                    <?php endif ?>
                    <hr>
                    <h3 class="text-center">Don't have an account?</h3>
                    <p class="text-center">
                        <a class="btn btn-default" href="<?=$app->router->absurl('register')?>">Register</a>
                    </p>
                </div> <!-- END: mainbar -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-lg-offset-1">
                    <?php include_once("../common/sponsor_sidebar.php") ?>
                </div> <!-- END: sidebar -->
            </div>
        </div>
        <?php include("../common/gen-footer.php"); ?>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js" type="text/javascript"></script>
        <script src="//ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/additional-methods.min.js" type="text/javascript"></script>
        <script type="text/javascript" src="<?=$app->router->asset('js/register.js')?>"></script>
    </body>
</html>