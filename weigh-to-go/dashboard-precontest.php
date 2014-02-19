<?php
    include('app/app.php');
    if(!isset($_SESSION['user'])) {
        $app->router->route("login");
    }
    try {
        $user = $app->getUserByEmail($_SESSION['user']);
    } catch (UserException $e) {
        $app->router->route("login");
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?=$user->fullName()?> | <?=App::CONTEST_NAME?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="Excercise, lose weight and get healthy with friends.">
        <meta name="apple-mobile-web-app-title" content="<?=App::CONTEST_NAME?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?= $app->router->asset('css/base.css')?>">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
            <style type="text/css" media="screen">
                .container {
                    max-width: 960px;
                }
            </style>
        <![endif]-->
        <?php include_once('/gmti/www/wilmingt/www/common/genericphp/ody-adtech.php'); ?>
    </head>
    <body>
        <?php include_once("common/gen-header.php"); ?>
        <div id="fb-root"></div>
        <script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=556602104404405"; fjs.parentNode.insertBefore(js, fjs);}(document, 'script', 'facebook-jssdk'));</script>
        <div class="container main">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-6 col-lg-offset-1">
                    <!-- <img src="" alt="Big Garrow Illustration" class="art-main"> -->
                    <br>
                    <h1 class="text-center"><?=App::CONTEST_NAME?></h1>
                    <h2 class="text-center">Welcome to the challenge</h2>
                    <p>
                        You are on your way to dropping pounds and gaining healthy habits. The official contest starts March 4, so mark your calendar.
                    </p>
                    <hr>
                    <p>
                        <strong>Register as an individual but want to join a group? <a href="<?= $app->router->absurl('edit-profile') ?>">Click here</a> to edit your profile.</strong>
                    </p>
                    <hr>
                    <h3>Prizes:</h3>
                    <ul>
                        <li>1-year YMCA Family Membership</li>
                        <li>1-year YMCA Adult Membership</li>
                        <li>Longwood Gardens Tickets</li>
                        <li>Delaware Children's Museum Passes</li>
                        <li>Hagley Museum Passes</li>
                        <li>Nouveau Cosmetic Center Beauty Baskey</li>
                        <li>$250 Visa Gift Card</li>
                        <li>$1,000 Visa Gift Card</li>
                        <li>More to come...</li>
                    </ul>
                    <hr>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-lg-offset-1">
                    <?php include_once("common/sponsor_sidebar.php") ?>
                </div> <!-- END: sidebar -->
            </div>
        </div>
        <?php include("common/gen-footer.php"); ?>

        <script src="http://code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    </body>
</html>