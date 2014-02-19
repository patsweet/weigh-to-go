<div class="header visible-lg">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 text-center">
                <p></p>
            </div>
            <div class="col-lg-6">
                <?php if ($user): ?>
                    <div class="btn-group user-menu">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                            <?=$user->fullName()?> <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="<?= $app->router->absurl('index') ?>">Home</a></li>
                            <?php if ($app->isAdmin($user)): ?>
                                <li><a href="<?=$app->router->absurl('admin')?>">Admin</a></li>
                            <?php endif ?>
                            <li><a href="<?= $app->router->absurl('edit-profile') ?>">Edit Profile</a></li>
                            <li><a href="<?= $app->router->absurl('password-reset') ?>">Change Password</a></li>
                            <li><a href="<?= $app->router->absurl('logout') ?>">Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <ul class="nav nav-pills">
                            <li><a href="<?= $app->router->absurl('login') ?>">Login</a></li>
                            <li><a href="<?= $app->router->absurl('register') ?>">Register</a></li>
                        <li><a href="<?= $app->router->absurl('index') ?>">Home</a></li>
                    </ul>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>
<div class="header-mobile hidden-lg">
    <div class="container">
        <div class="row">
            <div class="col-xs-12">
                <h1><a href="<?= $app->router->absurl('index')?>"><?=strtoupper(App::CONTEST_NAME)?></a></h1>
            </div>
            <div class="tnj-buttons">
                <div class="col-xs-6">
                    <?php if ($user): ?>
                        <a class="btn btn-default btn-block" href="<?= $app->router->absurl('logout')?>">Logout</a>
                    <?php else: ?>
                        <a class="btn btn-default btn-block" href="<?= $app->router->absurl('login')?>">Login</a>
                    <?php endif ?>
                </div>
                <div class="col-xs-6">
                    <?php if ($user): ?>
                        <a class="btn btn-default btn-block" href="<?= $app->router->absurl('password-reset')?>">Change Password</a>
                    <?php else: ?>
                        <a class="btn btn-default btn-block" href="<?= $app->router->absurl('register')?>">Register</a>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>
</div>