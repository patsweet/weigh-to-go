<?php

    include_once('../app.php');

    function testEmailEncode() {
        global $app;
        $conn = $app->conn;
        $emails = $conn->query("SELECT email FROM users");
        $counter = 0;
        while ($row = $emails->fetch()) {
            $encoded = $app->encode($row[0]);
            if ($row[0] != $app->decode($encoded)) {
                echo "Problem decoding {$row[0]}.<br>";
            }
            $counter++;
        }
        echo "Successfully encoded/decoded $counter emails.";
    }
    testEmailEncode();

?>