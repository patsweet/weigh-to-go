<?php

class RouterException extends Exception {}
class Router {
    protected $base_url;

    public function __construct($base_url) {
        if (substr($base_url, -1) == "/") {
            $this->base_url = $base_url;
        } else {
            $this->base_url = $base_url . "/";
        }
    }

    public function route($page) {
        header( "Location: " . $this->absurl($page) );
    }

    public function absurl($page) {
        switch ($page) {
            // Main pages.
            case 'index':
                $uri = '';
                break;
            case 'dashboard':
                $uri = 'dashboard-precontest.php';
                break;
            case 'dashboard-pre':
                $uri = 'dashboard-precontest.php';
                break;
            // Account management
            case 'login':
                $uri = 'accounts/login.php';
                break;
            case 'register':
                $uri = 'accounts/register.php';
                break;
            case 'edit-profile':
                $uri = 'accounts/edit-user.php';
                break;
            case 'logout':
                $uri = 'accounts/logout.php';
                break;
            case 'activate':
                $uri = 'accounts/activate.php';
                break;
            case 'password-reset':
                $uri = 'accounts/password-reset.php';
                break;
            case 'forgot-password':
                $uri = 'accounts/forgot-password.php';
                break;
            case 'survey':
                $uri = 'accounts/survey.php';
                break;
            // Admin page
            case 'admin':
                $uri = 'admin';
                break;
            default:
                throw new RouterException('Invalid Route.');
                break;
        }
        return $this->base_url . $uri;
    }
    public function asset($asset) {
        return $this->base_url . $asset;
    }
}

?>