<?php
    class UserException extends Exception {}


    class User extends Model {
        public $id;
        public $active = false;
        protected $email;
        protected $password;
        public $firstname;
        public $lastname;
        protected $startweight;
        public $group;
        public $presurvey;
        public $postsurvey;
        public $oGroup;
        public $oTracking;

        public function __construct() {
            $this->dbConnect();
            if ( isset($this->id) ) {
                $this->getGroup();
                $this->getTracking();
            }
            if ($this->oTracking) {
                usort($this->oTracking, array($this, 'date_cmp'));
            }
        }

        public function __destruct() {
            $this->conn = null;
        }

        public function __toString() {
            return $this->fullName();
        }
        /**
         * Overloaded function from Model class. Only returns a value if the object
         * is invalid.
         * @return string Only returned if model is invalid.
         */
        public function validate() {
            // Check for required fields
            if ( !isset($this->active, $this->email, $this->password, $this->firstname, $this->lastname, $this->startweight) ) {
                return "One or more required fields are blank or incorrect.";
            }
            // Check that the e-mail address looks valid.
            // This gets double-checked with a confirmation e-mail.
            if ( !$this->isValidEmail($this->email) ) {
                return "The email address does not appear to be valid.";
            }
            if ($this->startweight < 0) {
                return "Start weight must be greater than 0.";
            }
            // If no ID is set (new user), check that the
            // e-mail is not already in use.
            if ( !isset($this->id) ) {
                $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
                $stmt->execute(array("email"=>$this->email));
                if ( $stmt->fetch(PDO::FETCH_NUM) > 0 ) {
                    return "A user with that e-mail address already exists. Try <a href='login.php'>logging in</a>.";
                }
            }
        }
        /**
         * Saves/updates data on the database.
         * @return None
         */
        public function save() {
            $not_valid = $this->validate();
            if ($not_valid) {
                throw new UserException($not_valid);
            }
            if ( isset($this->id) ) {
                $stmt = $this->conn->prepare("UPDATE users u SET u.active = :active, u.email = :email, u.password = :password, u.firstname = :firstname, u.lastname = :lastname, u.group = :group, u.presurvey = :presurvey, u.postsurvey = :postsurvey WHERE u.id = :id;");
                $stmt->execute(array(
                    "id"=>(int)$this->id,
                    "active"=>(int)$this->active ? 1 : 0,
                    "email"=>$this->email,
                    "password"=>$this->password,
                    "firstname"=>$this->firstname,
                    "lastname"=>$this->lastname,
                    "group"=>(int)$this->group ? (int)$this->group : null,
                    "presurvey"=>(int)$this->presurvey ? 1 : 0,
                    "postsurvey"=>(int)$this->postsurvey ? 1 : 0
                ));
            } else {
                $stmt = $this->conn->prepare("INSERT INTO users (`active`, `email`, `password`, `firstname`, `lastname`, `group`, `presurvey`, `postsurvey`) VALUES (:active, :email, :password, :firstname, :lastname, :group, :presurvey, :postsurvey);");
                $stmt->execute(array(
                    "active"=>(int)$this->active ? 1 : 0,
                    "email"=>$this->email,
                    "password"=>$this->password,
                    "firstname"=>$this->firstname,
                    "lastname"=>$this->lastname,
                    "group"=>(int)$this->group ? (int)$this->group : null,
                    "presurvey"=>(int)$this->presurvey ? 1 : 0,
                    "postsurvey"=>(int)$this->postsurvey ? 1 : 0
                ));
                $this->id = $this->conn->lastInsertId();
            }
        }
        /**
         * Fetchs the group attributes from the databases.
         * @return boolean Only returned if group not found.
         */
        private function getGroup() {
            if ($this->group) {
                $stmt = $this->conn->prepare("SELECT * FROM groups WHERE id = :id;");
                $stmt->setFetchMode(PDO::FETCH_CLASS, "Group");
                $stmt->execute(array("id"=>$this->group));
                if ($group = $stmt->fetch()) {
                    $this->oGroup = $group;
                } else {
                    return false;
                }
            }
        }
        /**
         * Pulls all tracking from the database for the given user and stores
         * it in the $oTracking property.
         * @return boolean Only returned if tracking does not exists.
         */
        private function getTracking() {
            $stmt = $this->conn->prepare("SELECT * FROM track WHERE user = :id;");
            $stmt->setFetchMode(PDO::FETCH_CLASS, "Track");
            $stmt->execute(array("id"=>$this->id));
            if ($tracking = $stmt->fetchAll()) {
                $this->oTracking = $tracking;
            } else {
                return false;
            }
        }
        /**
         * Compares two dates and returns true if A is earlier.
         * @param  string $a A representation of a date.
         * @param  string $b A representation of a date
         * @return boolean    True if a < b.
         */
        public static function date_cmp($a, $b) {
            return date('Y-m-d', strtotime($a->track_date)) < date('Y-m-d', strtotime($b->track_date));
        }
        /**
         * Grabs the latest tracked weight if tracking exists, else the starting weight.
         * @return float The lastest weight of the user.
         */
        public function latestWeight() {
            if ($this->oTracking) {
                return $this->oTracking[0]->weight;
            } else {
                return $this->getStartWeight();
            }
        }

        public function getEmail() {
            return $this->email;
        }
        public function setEmail($email) {
            $this->email = $email;
        }

        public function getStartWeight() {
            return $this->startweight;
        }
        public function setStartWeight($weight) {
            $this->startweight = round((float)$weight, 1);
        }
        public function fullName() {
            return $this->firstname . ' ' . $this->lastname;
        }
        /**
         * Takes a raw password from the user and checks to see if it matches the
         * encrypted version in the database.
         * @param  string $password Raw password of the user.
         * @return boolean           Whether the passwords matched.
         */
        public function checkPassword($password) {
            if ( crypt($password, $this->password) == $this->password ) {
                return true;
            }
            return false;
        }
        /**
         * Hashes and encodes a user's password to make it suitable to
         * store in MySQL. Stores the result in the $password property.
         * @param string  $password The raw password of the user.
         * @param integer $cost     Cost of the hashing function.
         */
        public function setPassword($password, $cost=10) {
            $salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
            $salt = sprintf("$2a$%02d$", $cost) . $salt;
            $hash = crypt($password, $salt);
            $this->password = $hash;
        }
        public function activateUser() {
            if ( isset($this->id) ) {
                $this->active = true;
                $stmt = $this->conn->prepare('UPDATE users SET active = 1 WHERE id = :id;');
                $stmt->execute(array('id'=>$this->id));
            }
        }
        public function deleteUser() {
            if ( isset($this->id) ) {
                $stmt = $this->conn->prepare('DELETE FROM users WHERE id = :id;');
                $stmt->execute(array('id'=>$this->id));
                $this->id = null;
            }
        }
    }
?>