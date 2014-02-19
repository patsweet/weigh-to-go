<?php
    include('../app/app.php');
    $user = $app->getUserByEmail($_SESSION['user']);
    if ( !$user || !$app->isAdmin($user) ) {
        $app->router->route('dashboard');
    }
    $conn = $app->conn;
    if ($_POST['activate_form']) {
        if ( $_POST['activate_users'] ) {
            foreach ($_POST['activate_users'] as $uid) {
                $stmt = $conn->prepare("UPDATE users SET active = 1 WHERE id = :uid;");
                $stmt->execute(array(
                    'uid'=>(int)$uid
                ));
            }
            echo "Changed the status of " . count($_POST['activate_users']) . " users.<br>";
        }
        if ( isset($_POST['email_users']) && !empty($_POST['email_users']) ) {
            foreach ($_POST['email_users'] as $uemail) {
                sendActivationEmail($uemail);
            }
            echo "Sent activation emails to: " . join(", ", $_POST['email_users']) . "<br>";
        }
        die();
    }
    if ( $_POST['addgroupform'] == '1' && strlen($_POST['groupname']) > 0 ) {
        $stmt = $conn->prepare("INSERT INTO groups (group_name) VALUES (:groupname);");
        $stmt->execute(array("groupname"=>$_POST['groupname']));
    }
    if ( $_POST['randomUser'] ) {
        if (!$_POST['datetoweek'] ) {
            die();
        }
        $stmt = $conn->prepare("SELECT u.* FROM users u JOIN track t ON (u.id = t.user) WHERE WEEK(t.track_date) = WEEK(DATE(:datetoweek)) ORDER BY RAND() LIMIT 1;");
        $stmt->execute(array(
            "datetoweek"=>User::sanitizeDate($_POST['datetoweek'])
        ));
        $data = $stmt->fetch();
        echo "<strong>".$data['firstname']." ".$data['lastname']."!</strong> You can notify him/her at <a href='mailto:".$data['email']."'>".$data['email']."</a>";
        die();
    }
    if ($_POST['emailusers']) {
        $test = strip_tags($_POST['emailbody']);
        if ($test == '') {
            die("Something broke. Entry:<br>" . $test);
        }
        if ($_POST['sendconfirm'] == 'Yes!') {
            // mail("psweet@delawareonline.com", $_POST['subject'], $_POST['emailbody'], "From: Keep The Beat <keepthebeat-noreply@delawareonline.com>\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8");
            $users = $conn->query("SELECT DISTINCT email FROM users WHERE active = 1;");
            foreach ($users as $u) {
                mail($u['email'], $_POST['subject'], $_POST['emailbody'], "From: Keep The Beat <keepthebeat-noreply@delawareonline.com>\r\nMIME-Version: 1.0\r\nContent-Type: text/html; charset=utf-8");
            }
        }
        echo "<div class='well'><h3>Email-Example:</h3><p><strong>Subject:</strong><br>".$_POST['subject']."</p><strong>Body:</strong><div>".$_POST['emailbody']."</div></div>";
        die();
    }
?>
<html>
    <head>
        <title>
            ADMIN PANEL | Keep the Beat
        </title>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/css/jquery.dataTables.css">
        <link rel="stylesheet" href="<?=$app->router->asset('css/tinyeditor.css')?>">
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
        <style>
            .tinyeditor select {
                width: 128px;
                height: 25px;
                font-size: 12px;
                line-height: 12px;
            }
        </style>
        <?php include_once('/gmti/www/wilmingt/www/common/genericphp/ody-adtech.php'); ?>
    </head>
    <body style="padding-top: 40px;">
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">Weigh-to-Go!</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="<?=$app->router->absurl('admin')?>">Admin Panel</a></li>
                        <li><a href="<?=$app->router->absurl('dashboard')?>">User Dashboard</a></li>
                    </ul>
                    <p class="navbar-text navbar-right">
                        Signed in as <?=$user->fullName()?>
                    </p>
                </div><!-- /.navbar-collapse -->
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="page-header">
                        <h1>Admin Panel</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-3" >
                    <div class="well">
                        <h3>Quick Stats</h3>
                        <ul>
                            <li>
                                <?=number_format($conn->query("SELECT COUNT(*) FROM users WHERE active = 1;")->fetchColumn(), 0, ".", ",")?> active users
                            </li>
                            <li>
                                <?=$conn->query("SELECT COUNT(*) FROM users WHERE active = 0;")->fetchColumn()?> unactivated users
                            </li>
                            <li>
                                <?php $cnt = $conn->query("SELECT SUM(value) FROM track;")->fetchColumn(); echo number_format($cnt ? $cnt : 0, 0, ".", ","); ?> lbs lost
                            </li>
                            <li>
                                <?php $cnt = $conn->query("SELECT (SELECT SUM(value) FROM track) / (SELECT COUNT(*) FROM users WHERE active = 1);")->fetchColumn(); echo number_format($cnt ? $cnt : 0, 2, ".", ","); ?> lbs/user
                            </li>
                        </ul>
                    </div>
                    <div class="well" data-spy="affix">
                        <ul class="nav nav-pills nav-stacked">
                            <li><a href="#email-users">Email active users</a></li>
                            <li><a href="#users-by-group">Users by group</a></li>
                            <li><a href="#add-group">Add group</a></li>
                            <li><a href="#activation-email">Modify user status</a></li>
                            <li><a href="#draw-user">Draw random user</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-xs-9">
                    <div class="row" id="email-users">
                        <div class="col-xs-12">
                            <h2>E-mail users</h2>
                            <div id="email_form" style="margin-bottom: 10px;">
                                <fieldset>
                                    <label for="subject">Subject</label>
                                    <input name="subject" type="text" placeholder="Subject" id="subject">
                                    <label for="emailbody">Body</label>
                                    <textarea style="width=80%; height=200px" name="emailbody" id="tinyeditor" rows="10"></textarea>
                                    <label class="checkbox">
                                        <input type="checkbox" id="sendconfirm"> Send to all users (leave unchecked to see a preview below)
                                    </label>
                                </fieldset>
                                <button id="sendemails" class="btn btn-lg">Send</button>
                            </div>
                            <div id="email-response"></div>
                        </div>
                    </div>
                    <div class="row" id="users-by-group">
                        <hr>
                        <h2>Users by group</h2>
                        <select name="group_select" id="group_select">
                            <option value="">All Groups</option>
                            <?php
                                $data = $conn->query("SELECT DISTINCT group_name FROM groups ORDER BY 1;");
                                foreach ($data as $g) {
                                    echo "<option value='".$g["group_name"]."'>".$g["group_name"]."</option>";
                                }
                            ?>
                        </select>
                        <table id="user-groups-table">
                            <caption>Users in groups</caption>
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Group</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $data = $conn->query("SELECT firstname, lastname, email, group_name FROM users u JOIN groups g ON g.id = u.group ORDER BY g.group_name;");
                                    foreach($data as $u) {
                                        echo "<tr><td>".$u["firstname"]." ".$u["lastname"]."</td><td>".$u["email"]."</td><td>".$u["group_name"]."</td></tr>";
                                    }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row" id="add-group">
                        <hr>
                        <h2>Manage Groups</h2>
                        <form action="<?=$_SERVER['PHP_SELF']?>" method="POST" id="add-group-form">
                            <div class="form-group">
                                <label for="groupname">Add group</label>
                                <input type="text" class="form-control" name="groupname">
                            </div>
                            <input type="hidden" name="addgroupform" value="1">
                            <input type="submit" class="btn btn-default" value="submit">
                        </form>
                        <table id="groups-table" class="table table-condensed">
                            <caption>Groups</caption>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Members</th>
                                    <!-- <th>Edit</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $data = $conn->query("SELECT g.id, g.group_name, COUNT(DISTINCT u.id) AS user_count FROM groups g LEFT JOIN users u ON (u.group = g.id) GROUP BY 1 ORDER BY 1; ");
                                    foreach ($data as $g) {
                                        echo "<tr>";
                                        echo "<td>" . $g['group_name'] . "</td>";
                                        echo "<td>" . $g['user_count'] . "</td>";
                                        // echo "<td>" . $g['id'] . "</td>";
                                        echo "</tr>";
                                    }
                                 ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row" id="activation-email">
                        <hr>
                        <h2>Modify user status</h2>
                        <table id="user-status-table" class="table table-condensed">
                            <caption>Non-active users</caption>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>Send mail</th>
                                    <th>Make active</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $data = $conn->query("SELECT * FROM users WHERE active = 0 ORDER BY lastname;", PDO::FETCH_OBJ);
                                foreach($data as $u) {
                                    echo "<tr><td>".$u->firstname." ".$u->lastname."</td><td>".$u->email."</td><td><input class=\"sendEmail\" type=\"checkbox\" name=\"sendEmail\" value=\"".$u->id."\"></td><td><input class=\"makeActive\" type=\"checkbox\" name=\"makeActive\" value=\"".$u->id."\"></td></tr>";
                                }
                            ?>
                            </tbody>
                        </table>
                        <br>
                        <button id="user-status-btn" class="btn btn-xlarge">Submit</button>
                        <div id="modify-user-output" class="well"></div>
                    </div>
                    <div class="row" id="draw-user">
                        <hr>
                        <h2>Draw a random user!</h2>
                        <input type="date" id="random-user-date" name="datetoweek" value="<?=Date('Y-m-d')?>">
                        <button class="btn btn-xlarge" id="draw-winner-button">Draw!</button>
                        <div class="well" style="margin-top:10px;">
                            <h4>And the winner is...</h4>
                            <p id="draw-winner"></p>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>
        <script src="http://code.jquery.com/jquery.js"></script>
        <script src="<?=$app->router->asset('libs/tiny.editor.packed.js')?>" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="//netdna.bootstrapcdn.com/bootstrap/3.0.3/js/bootstrap.min.js"></script>
        <script type="text/javascript" charset="utf8" src="http://ajax.aspnetcdn.com/ajax/jquery.dataTables/1.9.4/jquery.dataTables.min.js"></script>
        <script>
            $(function() {
                var editor = new TINY.editor.edit('editor', {
                    id: 'tinyeditor',
                    width: "80%",
                    height: 200,
                    cssclass: 'tinyeditor',
                    controlclass: 'tinyeditor-control',
                    rowclass: 'tinyeditor-header',
                    dividerclass: 'tinyeditor-divider',
                    controls: [
                        'bold', 'italic', 'underline', 'strikethrough', '|', 'orderedlist', 'unorderedlist', '|', 'outdent', 'indent', '|', 'leftalign', 'centeralign', 'rightalign', 'blockjustify', '|', 'unformat', '|', 'undo', 'redo', 'n', 'font', 'size', 'style', '|', 'image', 'hr', 'link', 'unlink'
                    ],
                    footer: true,
                    fonts: ['Arial','Verdana','Georgia','Trebuchet MS'],
                    bodyid: 'editor',
                    footerclass: 'tinyeditor-footer',
                    toggle: {text: 'source', activetext: 'wysiwyg', cssclass: 'toggle'},
                    resize: {cssclass: 'resize'}
                });

                var user_status_table = $("#user-status-table").dataTable();
                var groups_table = $("#groups-table").dataTable();
                var user_group_table = $("#user-groups-table").dataTable({
                    "aaSorting": [[2,'asc']]
                });
                $("#user-groups-table_length").after(
                    $("#group_select")
                        .css("float", "left")
                        .css("margin", "0 3.25%")
                        .change(function() {
                            user_group_table.fnFilter($(this).val(), 2);
                        })
                );

                // Send e-mails to all users.
                var sendEmails = function() {
                    $("#sendemails").addClass("disabled").unbind("click");
                    editor.post();
                    $.ajax({
                        type:"POST",
                        url: "<?=$_SERVER['PHP_SELF']?>",
                        data: {
                            "emailusers": true,
                            "subject": $("#subject").val(),
                            "emailbody": $("#tinyeditor").val(),
                            "sendconfirm": $("#sendconfirm").is(":checked") ? "Yes!" : false
                        },
                        success: function(response) {
                            if ( $("#sendconfirm").is(":checked") ) {
                                alert("Your e-mail was sent!")
                            }
                            $("#sendconfirm").prop("checked", false);
                            $("#email-response").html(response);
                            $("#sendemails").removeClass("disabled").click(sendEmails);
                        }
                    });
                };
                $("#sendemails").click(sendEmails);

                // Draw a random user.
                $("#draw-winner-button").click(function() {
                    $.ajax({
                        type: "POST",
                        url: "<?=$_SERVER['PHP_SELF']?>",
                        data: {
                            "randomUser": true,
                            "datetoweek": $("#random-user-date").val()
                        },
                        success: function(response) {
                            $("#draw-winner").html(response);
                        }
                    })
                });

                // Modify the status of a user.
                $("#user-status-btn").click(function(){
                    var email_users = new Array();
                    var activate_users = new Array();
                    $("#user-status-table .sendEmail:checked").each(function() {
                        var e_mail = $(this).closest('td').prev('td').html();
                        email_users.push(e_mail);
                    });
                    $("#user-status-table .makeActive:checked").each(function() {
                        activate_users.push($(this).val());
                    });
                    console.log(email_users);
                    console.log(activate_users);
                    $.ajax({
                        type: "POST",
                        url: "<?=$_SERVER['PHP_SELF']?>",
                        data: {"email_users": email_users, "activate_users": activate_users, "activate_form": true},
                        success: function(response) {
                            $("#modify-user-output").html(response);
                        }
                    });
                });
            });
        </script>
    </body>
</html>