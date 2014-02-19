<?php
    class NotImplementedException extends Exception {}


    class Model {
        private $dbServer = '';
        private $dbUser = '';
        private $dbPass = '';
        private $dbName = 'weightogo';
        protected $conn;
        /**
         * Creates a connection to the MySQL database.
         * @return PDO
         */
        public function dbConnect() {
            try {
                $this->conn = new PDO('mysql:host='.$this->dbServer.';dbname='.$this->dbName, $this->dbUser, $this->dbPass);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die('Error:' . $e->getMessage());
            }
        }
        /**
         * Conforms a date to the MySQL standard of YYYY-MM-DD
         * @param  string $date Date to sanitize
         * @return string       The conformed date.
         */
        public static function sanitizeDate($date) {
            return date('Y-m-d', strtotime($date));
        }
        /**
         * Performs a basic check on an e-mail address to determine whether
         * it appears to be valid.
         * @param  string  $email E-mail address to check.
         * @return boolean        Whether it's valid.
         */
        public static function isValidEmail($email) {
            return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
        }
        /**
         * Boilerplate function that subclasses should overwrite. For subclasses, it should
         * only return a string if the object is not valid, similar in style to Backbone.js's
         * validation method.
         */
        public function validate() {
            throw new NotImplementedException("Validation method not implemented in class: " . get_class(this));
        }
        /**
         * Boilerplate function that subclasses should overwrite to save/update data in
         * the database.
         */
        public function save() {
            throw new NotImplementedException("Save method not implemented in class: " . get_class(this));
        }
    }
?>