<?php
    session_set_cookie_params(3600);
    session_start();
    session_regenerate_id();
    date_default_timezone_set('America/New_York');
    include_once('router.php');
    include_once('models/model.php');
    include_once('models/track.php');
    include_once('models/group.php');
    include_once('models/user.php');
    include_once('models/presurvey.php');

    class App {
        /**
         * Url routing.
         * @var Router
         */
        public $router;
        /**
         * Connection to MySQL Database
         * @var PDO
         */
        public $conn;
        /**
         * List of approved admins.
         * @var array
         */
        protected $admins = array(
            // Array of admin email addresses.
        );
        protected $secret_key = '';
        protected $dbhost = '';
        protected $dbuser = '';
        protected $dbpass = '';
        CONST CONTEST_NAME = 'Weigh-to-Go!';
        CONST BASE_URL = 'http://php.delawareonline.com/news/2014/weigh-to-go';
        CONST EMAIL_FROM = 'weightogo-noreply@delawareonline.com';
        public $start_date = "2014-03-04";
        public function __construct() {
            $this->router = new Router(self::BASE_URL);
            $this->conn = $this->dbConnect();
            $this->calcWeeks();
        }
        public function __destruct() {
            $this->conn = null;
        }
        private function dbConnect() {
            try {
                $conn = new PDO('mysql:host='.$this->dbhost.';dbname=weightogo', $this->dbuser, $this->dbpass);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Error: ' . $e->getMessage());
            }
            return $conn;
        }
        /**
         * Internal helper function to abstract creation of array of weeks' start
         * and end dates. Allows us to quickly change the starting days.
         * @return None
         */
        private function calcWeeks() {
            $weeks = array();
            $start_date = $this->start_date;
            for ($i=1; $i < 11; $i++) {
                $weeks['Week '. $i] = array(
                    $start_date,
                    date("Y-m-d", strtotime($start_date . " +6 days"))
                );
                $start_date = date("Y-m-d", strtotime($start_date . " +1 week"));
            }
            $this->weeks = $weeks;
        }
        /**
         * Takes an input date and returns the Week number of the contest. Because
         * the contest starts on a Tuesday, we have to calc custom week numbers.
         * @param  string $date A date in the form of YYYY-MM-DD.
         * @return int       Number of the week. 0 if outside contest dates.
         */
        public function getWeek($date) {
            for ($i=0; $i < count($this->weeks); $i++) {
                if ($date >= $this->weeks[$i][0] && $date <= $this->weeks[$i][1] ) {
                    return $i + 1;
                }
            }
            return 0;
        }
        /**
         * Check if the user is an Administrator.
         * @param  User    $user User to evaluate.
         * @return boolean
         */
        public function isAdmin(User $user) {
            return in_array($user->getEmail(), $this->admins);
        }
        /**
         * Pulls a user instance from the MySQL Databases.
         * @param  string $email E-mail of the users.
         * @return User/boolean        Returns a User object if exists, else false.
         */
        public function getUserByEmail($email) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email;");
            $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
            $stmt->execute(array("email"=>$email));
            if ($user = $stmt->fetch()) {
                return $user;
            } else {
                return false;
            }
        }
        /**
         * Pulls a user instance from MySQL database by ID
         * @param  int $id Unique ID of the user
         * @return User/boolean     Returns user if exists, else false.
         */
        public function getUserById($id) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = :id;");
            $stmt->setFetchMode(PDO::FETCH_CLASS, "User");
            $stmt->execute(array("id"=>$id));
            if ($user = $stmt->fetch()) {
                return $user;
            } else {
                return false;
            }
        }
        /**
         * Helper function to get a Group class by ID.
         * @param  int $id Unique ID of the Group.
         * @return User/boolean     Returns Group if exists, else false.
         */
        public function getGroupById($id) {
            $stmt = $this->conn->prepare("SELECT * FROM groups WHERE id = :id;");
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Group");
            $stmt->execute(array("id"=>$id));
            if ($group = $stmt->fetch()) {
                return $group;
            } else {
                return false;
            }
        }
        /**
         * Helper function to retrieve Tracking by ID
         * @param  int $id Unique ID of the invidual tracking object.
         * @return Track/boolean     If Track exists, return track, else false.
         */
        public function getTrackById($id) {
            $stmt = $this->conn->prepare("SELECT * FROM track WHERE id = :id;");
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Track");
            $stmt->execute(array("id"=>$id));
            if ($track = $stmt->fetch()) {
                return $track;
            } else {
                return false;
            }
        }
        /**
         * Helper function to get all tracking activity by user.
         * @param  User   $user User whose tracking to request.
         * @return array/false       If tracking, return array of results, else false.
         */
        public function getTrackingByUser(User $user) {
            $stmt = $this->conn->prepare("SELECT * FROM track WHERE user = :user;");
            $stmt->execute(array(
                "user"=>$user->id
            ));
            if ($tracking = $stmt->fetchAll(PDO::FETCH_CLASS, "Track")) {
                return $tracking;
            } else {
                return false;
            }
        }
        /**
         * Encodes a string using the App's secret key.
         * @param  string  $str        The variable to encode.
         * @param  boolean $url_encode Whether to urlencode the response
         * @return string              The encoded variable.
         */
        public function encode($str, $url_encode=True) {
            $encoded_var = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($this->secret_key), $str, MCRYPT_MODE_CBC, md5(md5($this->secret_key))));
            if ($url_encode) {
                return urlencode($encoded_var);
            } else {
                return $encoded_var;
            }
        }
        /**
         * Decodes a string encoded with $this->encode().
         * @param  string  $str        The encoded string.
         * @param  boolean $url_decode Whether to urldecode the inputed string.
         * @return string              The decoded value.
         */
        public function decode($str, $url_decode=False) {
            if ($url_decode) {
                $str = urldecode($str);
            }
            return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($this->secret_key), base64_decode($str), MCRYPT_MODE_CBC, md5(md5($this->secret_key))), "\0");
        }
        /**
         * Encodes the users e-mail address and send the activation e-mail.
         * @param  string $email E-mail address of user
         * @return None
         */
        public static function sendActivationEmail($email) {
            $activate_link = self::BASE_URL . "/accounts/activate.php?q=" . $this->encode($email);
            $message = "Thanks for registering. You are almost done.\n\nPlease click the link below to confirm your e-mail address and activate your account.\n\n".$activate_link."\n\nIf clicking the link does not work, you might have to copy and paste it into your browser.";
            mail($email, "Weigh-to-Go: confirm e-mail", $message, "From: Weigh-to-Go <weightogo-noreply@delawareonline.com>\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=utf-8");
        }
        /**
         * Utility function to return JSON encoded errors. Especially useful for responses
         * to Ajax requests.
         * @param  string $type    Type of error, such as "warning".
         * @param  string $message The message to display to the user.
         * @return None
         */
        public static function jsonError($type, $message) {
            $error = array($type=>$message);
            echo json_encode($error);
            die();
        }
        /**
         * Sets the session's error variable.
         * @param string $error Message to store.
         */
        public static function setError($error) {
            $_SESSION['error'] = $error;
        }
        /**
         * Retrievs an error from the Session and unsets it so it doesn't
         * continually show up.
         * @return string Error message to display to user.
         */
        public static function getError() {
            $error = $_SESSION['error'] ? $_SESSION['error'] : false;
            if ( $error ) {
                unset( $_SESSION['error'] );
            }
            return $error;
        }
    }
    $app = new App;
?>