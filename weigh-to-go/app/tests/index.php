<?php

    include_once('../app.php');

    $user = getUserByEmail("psweet@delawareonline.com");
    if (!$user) {
        $user = new User();
    }
    // echo json_encode($user->checkPassword("Hello World"));

    $group = getGroupById(1);

    // echo json_encode($group->numUsers(false));
    echo json_encode(getTrackingByUser($user));
?>