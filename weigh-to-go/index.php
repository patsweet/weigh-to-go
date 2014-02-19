<?php
    include_once('app/app.php');
    if(isset($_SESSION['user'])) {
        $app->router->route('dashboard');
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?=App::CONTEST_NAME?></title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="Excercise, lose weight and get healthy with friends by competing in Keep The Beat ">
        <meta name="apple-mobile-web-app-title" content="<?=App::CONTEST_NAME?>">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="<?= $app->router->asset('css/base.css') ?>">
        <link rel="stylesheet" type="text/css" href="<?= $app->router->asset('css/index.css') ?>">
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
            <style type="text/css" media="screen">
                .container {
                    max-width: 1027px;
                }
            </style>
        <![endif]-->
        <?php include_once('/gmti/www/wilmingt/www/common/genericphp/ody-adtech.php'); ?>
    </head>
    <body>
        <?php include_once('common/gen-header.php'); ?>
        <div class="container main">
            <div class="row">
                <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                    <!-- <img src="" alt="Big Garrow Illustration" class="art-main"> -->
                    <br>
                    <p class="text-center">
                        <a class="btn btn-primary btn-lg" href="<?=$app->router->absurl('register')?>">Register Today</a>
                    </p>
                    <h1 class="text-center"><?=App::CONTEST_NAME?></h1>
                    <div class="row">
                        <h3 class="col-xs-12 text-center">Lose weight, win prizes</h3>
                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                            <p class="body-text">
                                Join the challenge and receive tips and strategies that can help you lose weight - safely and sensibly. Follow along and you could lose 10 pounds in 10 weeks! Weigh yourself every Monday and submit that number weekly. To keep you motivated, we'll be giving away prizes weekly and at the end of the challenge, including a <strong>$1,000 Visa gift card</strong> and <strong>YMCA memberships</strong>.
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                            <img class="lazy img-rounded body-photo" src="<?=$app->router->asset('images/cycling_class.jpg')?>">
                        </div>
                    </div>
                    <div class="row">
                        <h3 class="col-xs-12 text-center">Get tips from experts</h3>
                        <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                            <img class="lazy img-rounded body-photo" src="<?=$app->router->asset('images/health_food.jpg')?>">
                        </div>
                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                            <p class="body-text">
                                The goal is to adopt "tried and true" habits that work to shed unwanted excess weight. No fads, gimmicks or magic potions – just sensible suggestions from a Registered Dietitian. Every Tuesday in The News Journal's Health section (and online at this site), receive a specific weight loss strategy. Spend the week "practicing" that behavior. Let’s see if Weigh-to-Go participants can collectively lose 20,000 pounds!
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <h3 class="col-xs-12 text-center">Have fun with friends and coworkers</h3>
                        <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6">
                            <p class="body-text">
                                Everything is more fun with friends. Groups of 10 or more can sign up together and tackle the Weigh-to-Go! challenge together (contact <a href="mailto:kbothum@delawareonline.com">Kelly</a> or <a href="mailto:sherel@delawareonline.com">Suzzanne</a> to form a group). You can also get involved with other participants through the Weigh-to-Go! Facebook page. We're all in this together to get healthy.
                            </p>
                        </div>
                        <div class="col-xs-12 col-sm-4 col-md-6 col-lg-6">
                            <img class="lazy img-rounded body-photo" src="<?=$app->router->asset('images/yoga_stretch.jpg')?>">
                        </div>
                    </div>
                    <hr>
                    <p class="text-center">
                        <a class="btn btn-primary btn-lg" href="<?=$app->router->absurl('register')?>">Register Today</a>
                    </p>
                </div> <!-- END: mainbar -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <?php include_once("common/sponsor_sidebar.php") ?>
                </div> <!-- END: sidebar -->
            </div> <!-- END: row -->
        </div> <!-- END: container main -->
        <?php include("common/gen-footer.php"); ?>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    </body>
</html>