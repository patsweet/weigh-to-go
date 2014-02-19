<?php
    include('../app/app.php');
    if(!isset($_SESSION['user'])) {
        $app->router->route('login');
    }
    try {
        $user = $app->getUserByEmail($_SESSION['user']);
    } catch (UserException $e) {
        die('Error: ' . $e->getMessage());
    }

    function processForm() {
        global $user, $app, $error_messages;

        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $groupid = $_POST['group'];

        $user->firstname = strlen($firstname) > 0 ? $firstname : $user->firstname;
        $user->lastname = strlen($lastname) > 0 ? $lastname : $user->lastname;
        $user->group = $groupid != '' ? (int)$groupid : null;
        try {
            $user->save();
        } catch (Exception $e) {
            die($e->message());
        }
    }
    if ( !empty($_POST) ) {
        processForm();
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Edit Profile | <?=App::CONTEST_NAME?></title>
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
                    <h2>Edit <?=$user->fullName()?>'s profile</h2>
                    <form action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                        <div class="form-group">
                            <label class="control-label" for="firstname">First name:</label>
                            <input name="firstname" type="text" class="form-control" placeholder="First name" value="<?=$user->firstname?>" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="lastname">Last name:</label>
                            <input name="lastname" type="text" class="form-control" placeholder="Last name" value="<?=$user->lastname?>" required>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="group">Company/group:</label>
                            <select class="form-control" name="group">
                                <option value="">None</option>
                                <?php
                                    $data = $app->conn->query("SELECT * FROM groups ORDER BY group_name;", PDO::FETCH_OBJ);
                                    foreach($data as $group) {
                                        echo "<option value='".$group->id."' " . ((int)$group->id == (int)$user->group ? "selected" : "") . ">".$group->group_name."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" id="submit-button" class="btn btn-primary form-control" value="Save">
                        </div>
                    </form>
                </div> <!-- END: mainbar -->
                <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-lg-offset-1">
                    <?php include_once("../common/sponsor_sidebar.php") ?>
                </div> <!-- END: sidebar -->
            </div>
        </div>
        <?php include("../common/gen-footer.php"); ?>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
    </body>
</html>
