<?php
    include_once('../app/app.php');
    unset($_SESSION['user']);
    session_destroy();
    $app->router->route("index");
?>