<?php
    include('app/app.php');
    if(!isset($_SESSION['user'])) {
        $app->router->route("login");
    }
    try {
        $user = $app->getUserByEmail($_SESSION['user']);
    } catch (UserException $e) {
        die('Error: ' . $e->getMessage());
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?=$user->fullName()?> | Weigh-to-Go!</title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="Excercise, lose weight and get healthy with friends.">
        <meta name="apple-mobile-web-app-title" content="Weigh-to-Go">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <link rel="apple-touch-icon" href="<?= $app->router->asset('images/apple-icon.png')?>">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="<?= $app->router->asset('css/base.css')?>">
        <link rel="stylesheet" href="<?= $app->router->asset('css/dashboard.css')?>">
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
                <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
                    <h1 class="text-center"><?=App::CONTEST_NAME?></h1>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#progress" data-toggle="tab">Your Progress</a></li>
                        <?php if ($user->group): ?>
                            <li><a href="#group" data-toggle="tab"><?=$user->oGroup?></a></li>
                        <?php endif ?>
                        <li><a href="#prizes" data-toggle="tab">Prizes</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="progress">
                            <h2>This is where you'll find your progress.</h2>
                        </div>
                        <?php if ($user->group): ?>
                            <div class="tab-pane" id="group">
                                <h3>This is information about your group.</h3>
                            </div>
                        <?php endif ?>
                        <div class="tab-pane" id="prizes">
                            <p>
                                Weekly and grand prizes are awarded randomly. To be eligible, make sure to report your progress each week.
                            </p>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Prize</th>
                                        <th>Winner</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1-year YMCA Family Membership</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>1-year YMCA Adult Membership</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Longwood Gardens Tickets</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Delaware Children's Museum Passes</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Hagley Museum Passes</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>Nouveau Cosmetic Center Beauty Baskey</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>$250 Visa Gift Card</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>$1,000 Visa Gift Card</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td>More to come...</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
                    <?php include_once("common/sponsor_sidebar.php") ?>
                </div> <!-- END: sidebar -->
            </div>
        </div>
        <?php include("common/gen-footer.php"); ?>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="js/lib/jquery-dateFormat.min.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.0.0/backbone-min.js"></script>
        <!-- <script src="js/models/track.js"></script>
        <script src="js/collections/tracking.js"></script>
        <script src="js/views/track.js"></script>
        <script src="js/views/tracking.js"></script>
        <script src="js/views/manage.js"></script>
        <script src="js/dashboard.js"></script> -->
    </body>
</html>