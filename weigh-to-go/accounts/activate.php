<?php
    session_set_cookie_params(3600);
    session_start();
    session_regenerate_id();
    if(isset($_SESSION['user'])) {
        $app->router->route('dashboard');
    }
    include('../app/app.php');
    $email = base64_decode($_GET['q']);
    $user = $app->getUserbyEmail($email);
    if ($user) {
        $user->activateUser();
        $_SESSION['login_message'] = array("message"=>"<strong>Success!</strong> Thanks for activating. You may now login below.", "status"=>"success");
    } else {
        $_SESSION['login_message'] = array("message"=>"<strong>Whoops!</strong> Looks like something went wrong. Please try clicking the activation link again. Contact psweet@delawareonline.com if the error continues.", "status"=>"danger");
    }
    $app->router->route('login');
?>