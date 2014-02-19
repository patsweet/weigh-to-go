<?php
    class GroupException extends Exception {}


    class Group extends Model {
        public $id;
        public $group_name;

        public function __construct() {
            $this->dbConnect();
        }

        public function __destruct() {
            $this->conn = null;
        }

        public function __toString() {
            return $this->group_name;
        }

        public function numUsers($count_active = true) {
            if ($count_active) {
                $stmt = $this->conn->prepare("SELECT COUNT(*) AS num_users FROM users u WHERE u.active = 1 AND u.group = :id;");
                $stmt->execute(array(
                    "id"=>(int)$this->id
                ));
            } else {
                $stmt = $this->conn->prepare("SELECT COUNT(*) AS num_users FROM users u WHERE u.group = :id;");
                $stmt->execute(array(
                    "id"=>(int)$this->id
                ));
            }
            return (int)$stmt->fetchColumn();
        }

        public function sumTracking() {
            $stmt = $this->conn->prepare("SELECT SUM(t.value) FROM track t JOIN users u ON (t.user = u.id) JOIN groups g ON (g.id = u.group) WHERE u.group = :id;");
            $stmt->execute(array(
                "id"=>(int)$this->id
            ));
            return (float)$stmt->fetchColumn();
        }

        public function validate() {
            if ( !$this->group_name || strlen($this->group_name) < 1 ) {
                return "Must enter a group name.";
            }
        }

        public function save() {
            $not_valid = $this->validate();
            if ($not_valid) {
                throw new GroupException($not_valid);
            }
            if ( isset($this->id) ) {
                $stmt = $this->conn->prepare("UPDATE groups g SET g.group_name = :group_name WHERE g.id = :id;");
                $stmt->execute(array(
                    "id"=>(int)$this->id,
                    "group_name"=>$this->group_name
                ));
            } else {
                $stmt = $this->conn->prepare("INSERT INTO groups (group_name) VALUES (:group_name);");
                $stmt->execute(array(
                    "group_name"=>$this->group_name
                ));
                $this->id = $this->conn->lastInsertId();
            }
        }

    }
?>