<?php
    include('../app/app.php');
    if(isset($_SESSION['user'])) {
        $app->router->route('dashboard');
    }
    $show_success = false;
    $error_messages = array();

    function processForm() {
        global $app;
        global $show_success;
        global $error_messages;

        $reg_user = new User();
        $survey = new PreSurvey();

        $reg_user->firstname = $_POST['firstname'];
        $reg_user->lastname = $_POST['lastname'];
        $reg_user->setEmail($_POST['email']);
        $reg_user->group = $_POST['group'] != '' ? (int)$_POST['group'] : null;
        $reg_user->setStartWeight($_POST['weight']);
        $reg_user->presurvey = 1;
        if ($_POST['password'] == $_POST['password2']) {
            $reg_user->setPassword($_POST['password']);
        }
        $survey->setAge( $_POST['age'] );
        $survey->setGender( $_POST['gender'] );
        $survey->setEthnicity( $_POST['ethnicity'] );
        $survey->setCounty( $_POST['county'] );
        $survey->setZipCode( $_POST['zipcode'] );
        $survey->setWeight( $_POST['weight'] );
        $survey->setHeight( $_POST['height'] );
        $survey->q1 = (int)$_POST['q1'];
        $survey->q2a = $_POST['q2a'] == '1' ? 1 : 0;
        $survey->q2b = isset($_POST['q2b']) ? (int)$_POST['q2b'] : null;
        $survey->q3 = $_POST['q3'];
        $survey->q4 = (int)$_POST['q4'];
        $survey->q5 = (int)$_POST['q5'];

        try {
            $reg_user->save();
        } catch (UserException $e) {
            $message = $e->getMessage();
            // mail("psweet@delawareonline.com", "Registration Error", $message . "\r\n" . json_encode($_POST), "From: Keep The Beat <keepthebeat-noreply@delawareonline.com>\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8");
            array_push($error_messages, $message);
            return;
        }
        try {
            $survey->setUser( $reg_user );
            $survey->save();
        } catch (PreSurveyException $e) {
            $reg_user->deleteUser();
            $message = $e->getMessage();
            // mail("psweet@delawareonline.com", "Registration Error", $message . "\r\n" . json_encode($_POST), "From: Keep The Beat <keepthebeat-noreply@delawareonline.com>\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8");
            array_push($error_messages, $message);
            return;
        }
        if ( empty($error_messages) ) {
            $app->sendActivationEmail($_POST['email']);
            $show_success = true;
        }
        return;
    }

    if ($_POST) {
        processForm();
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Register | <?=App::CONTEST_NAME?></title>
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
                    <?php if (!$show_success || count($error_messages) > 0): ?>
                        <h1>Register</h1>
                        <p>
                            The information you provide is strictly confidential.  Data will be stored in such a way that individual participants will not be identifiable by name. Only group characteristics and results will be reported.
                        </p>
                        <form id="registration_form" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
                            <?php if (!empty($error_messages)): ?>
                                <div class="alert alert-danger alert-dismissable">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <strong>Whoops!</strong> Please fix the following errors:
                                    <ul>
                                        <?php foreach ($error_messages as $err): ?>
                                            <li><?=$err?></li>
                                        <?php endforeach ?>
                                    </ul>
                                </div>
                            <?php endif ?>
                            <div class="col-xs-12 col-lg-6">
                                <div class="form-group">
                                    <label class="control-label" for="firstname">First name:</label>
                                    <input name="firstname" type="text" class="form-control" placeholder="First name" value="<?=$_POST['firstname'];?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="lastname">Last name:</label>
                                    <input name="lastname" class="form-control" type="text" placeholder="Last name" value="<?=$_POST['lastname']?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="email">E-mail:</label>
                                    <input id="emailfield" class="form-control" name="email" type="email" placeholder="E-mail" value="<?=$_POST['email']?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="emailconfirm">Confirm e-mail:</label>
                                    <input id="emailconfirm" class="form-control" name="emailconfirm" type="email" placeholder="Confirm e-mail" value="<?=$_POST['emailconfirm']?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="password">Password:</label>
                                    <input id="pwd" class="form-control" name="password" type="password" minlength="8" required>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="password2">Confirm password:</label>
                                    <input id="pwd2" class="form-control" name="password2" type="password" minlength="8" required>
                                </div>
                            </div>
                            <div class="col-lg-6 col-xs-12">
                                <div class="form-group">
                                    <label class="control-label" for="group">Company/group:</label>
                                    <select class="form-control" name="group" id="">
                                        <option value="">None</option>
                                        <?php
                                            $data = $app->conn->query("SELECT * FROM groups ORDER BY group_name;", PDO::FETCH_OBJ);
                                            foreach($data as $group) {
                                                echo "<option value='".$group->id."' " . ((int)$group->id == (int)$_POST['group'] ? "selected" : "") . ">".$group->group_name."</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="age">
                                        Age
                                    </label>
                                    <input class="form-control" type="number" name="age" placeholder="Age" value="<?=$_POST['age']?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="gender">
                                        Gender
                                    </label>
                                    <select class="form-control" name="gender" id="gender">
                                        <option value="">---</option>
                                        <?php foreach (PreSurvey::$GENDER_OPTIONS as $gender): ?>
                                            <option value="<?=$gender?>" <?= $_POST['gender'] == $gender ? "selected" : ""?>><?= $gender == "M" ? "Male" : "Female" ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ethnicity">
                                        Ethnicity
                                    </label>
                                    <select class="form-control" name="ethnicity" id="">
                                        <option value="">---</option>
                                        <?php foreach (PreSurvey::$ETHNICITY_OPTIONS as $ethnicity): ?>
                                            <?php if ($ethnicity != "NA"): ?>
                                                <option value="<?=$ethnicity?>" <?= $_POST['ethnicity'] == $ethnicity ? "selected" : ""?>>
                                                    <?=ucwords(strtolower($ethnicity))?>
                                                </option>
                                            <?php endif ?>
                                        <?php endforeach ?>
                                        <option value="NA">Prefer not to answer</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="zipcode">
                                        ZIP code
                                    </label>
                                    <input class="form-control" type="text" name="zipcode" placeholder="Zip code" value="<?=$_POST['zipcode']?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="county">
                                        County
                                    </label>
                                    <select class="form-control" name="county" id="">
                                        <option value="">---</option>
                                        <option value="NCCO" <?= $_POST['county'] == "NCCO" ? "selected": ""?>>New Castle</option>
                                        <option value="KCO" <?= $_POST['county'] == "KCO" ? "selected": ""?>>Kent</option>
                                        <option value="SCO" <?= $_POST['county'] == "SCO" ? "selected": ""?>>Sussex</option>
                                        <option value="OUT" <?= $_POST['county'] == "OUT" ? "selected": ""?>>Out of state</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="q1">
                                    How many days per week do you currently engage in moderate physical activity (e.g., brisk walking, dancing, bicycling) for a total of at least 30 minutes?
                                </label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" name="q1" value="0" <?= $_POST['q1'] == "0" ? "checked" : ""?>> 0
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="q1" value="1" <?= $_POST['q1'] == "1" ? "checked" : ""?>> 1
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="q1" value="2" <?= $_POST['q1'] == "2" ? "checked" : ""?>> 2
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="q1" value="3" <?= $_POST['q1'] == "3" ? "checked" : ""?>> 3
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="q1" value="4" <?= $_POST['q1'] == "4" ? "checked" : ""?>> 4
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="q1" value="5" <?= $_POST['q1'] == "5" ? "checked" : ""?>> 5+
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="q2a">
                                    Have you been trying to lose weight during the past 3 months?
                                </label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" name="q2a" value="1" <?= $_POST['q2a'] == "1" ? "checked" : ""?>> Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="q2a" value="0" <?= $_POST['q2a'] == "0" ? "checked" : ""?>> No
                                    </label>
                                </div>
                            </div>
                            <div class="form-group" style="display:none;">
                                <label for="q2b">
                                    Have you been losing weight?
                                </label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" name="q2b" value="1" <?= $_POST['q2b'] == "1" ? "checked" : ""?>> Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="q2b" value="0" <?= $_POST['q2b'] == "0" ? "checked" : ""?>> No
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="q3">
                                    How many pounds do you feel you need to lose in order to be at a healthy weight?
                                </label>
                                <select class="form-control" name="q3" id="q3">
                                    <option value="">---</option>
                                    <option value="<10" <?= $_POST['q3'] == "<10" ? "selected" : ""?>>&lt; 10</option>
                                    <option value="10-20" <?= $_POST['q3'] == "10-20" ? "selected" : ""?>>10-20</option>
                                    <option value="20-30" <?= $_POST['q3'] == "20-30" ? "selected" : ""?>>20-30</option>
                                    <option value="30-40" <?= $_POST['q3'] == "30-40" ? "selected" : ""?>>30-40</option>
                                    <option value="40-50" <?= $_POST['q3'] == "40-50" ? "selected" : ""?>>40-50</option>
                                    <option value="50+" <?= $_POST['q3'] == "50+" ? "selected" : ""?>>50+</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="height">
                                    Height (inches)
                                </label>
                                <div class="input-group">
                                    <input class="form-control" type="number" name="height" placeholder="Height in inches" value="<?=$_POST['height']?>" required>
                                    <span class="input-group-addon">inches</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="weight">
                                    Weight
                                </label>
                                <div class="input-group">
                                    <input class="form-control" type="number" name="weight" placeholder="Weight in pounds" value="<?=$_POST['weight']?>" required>
                                    <span class="input-group-addon">lbs</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="bmi">
                                    BMI
                                </label>
                                <input type="text" class="form-control" name="bmi" value="<?=$_POST['bmi']?>" disabled>
                            </div>
                            <div class="form-group">
                                <label for="q4">
                                    Realistically, how many pounds would you like to lose during this 10-week challenge? (Keep in mind that a loss of 1-2 pounds per week is considered safe.)
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control" name="q4" placeholder="Weight in pounds" value="<?=$_POST['q4']?>">
                                    <span class="input-group-addon">lbs</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="q5">
                                    On a scale of 1-10, with 1 being "highly unlikely" and 10 being "very likely", how confident are you that you can lose weight?
                                </label>
                                <select name="q5" id="q5" class="form-control">
                                    <option value="">---</option>
                                    <option value="1"  <?= $_POST['q5'] == "1" ? "selected" : ""?>>1</option>
                                    <option value="2"  <?= $_POST['q5'] == "2" ? "selected" : ""?>>2</option>
                                    <option value="3"  <?= $_POST['q5'] == "3" ? "selected" : ""?>>3</option>
                                    <option value="4"  <?= $_POST['q5'] == "4" ? "selected" : ""?>>4</option>
                                    <option value="5"  <?= $_POST['q5'] == "5" ? "selected" : ""?>>5</option>
                                    <option value="6"  <?= $_POST['q5'] == "6" ? "selected" : ""?>>6</option>
                                    <option value="7"  <?= $_POST['q5'] == "7" ? "selected" : ""?>>7</option>
                                    <option value="8"  <?= $_POST['q5'] == "8" ? "selected" : ""?>>8</option>
                                    <option value="9"  <?= $_POST['q5'] == "9" ? "selected" : ""?>>9</option>
                                    <option value="10" <?= $_POST['q5'] == "10" ? "selected" : ""?>>10</option>
                                </select>
                            </div>
                            <p>
                                By registering, I pledge to join the "Be Healthy Delaware: <?=App::CONTEST_NAME?>" program, and will attempt to incorporate the suggestions offered throughout the 10-week program. My goal is to contribute to the collective pounds lost by everyone that registers, and to be more active by the program's end than I am currently.
                            </p>
                            <p>
                                If I am participating as part of a company or group, I grant permission to provide my e-mail address to the company contact. I understand that no other information will be shared and only aggregate data such as total pounds lost by my company will be released to the company contact.
                            </p>
                            <p>
                                Recommendations provided throughout the program are general, and do not take into account individual medical problems and physical limitations. These guidelines should not be used as a substitute for individual medical advice.  In no event will the Delaware Center for Health Promotion or The News Journal be liable for damages of any kind arising from my participation in this program.
                            </p>
                            <div class="form-group">
                                <label class="checkbox">
                                    <input id="contest-rules" name="contest-rules" type="checkbox" value=""> I agree to the <a href="/news/2014/weigh-to-go/common/contest-rules.htm" target="_blank">contest rules</a>.
                                </label>
                            </div>
                            <div class="form-group">
                                <button id="submit-button" class="btn btn-primary  form-control disabled" >Register</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="col-xs-8 col-xs-offset-2">
                            <h2>
                                Almost done!
                            </h2>
                            <p>
                                Check your inbox for an activation e-mail. Click or copy and paste the link in that e-mail into your browser to confirm your e-mail address.
                            </p>
                            <p>
                                Be sure to add <strong><?=App::EMAIL_FROM?></strong> to your safe senders list to help ensure the e-mail arrives in your inbox.
                            </p>
                        </div>
                    <?php endif; ?>
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
