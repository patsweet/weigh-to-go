<?php
    class TrackingException extends Exception {}


    class Track extends Model {
        public $id;
        public $user;
        public $track_date;
        public $weight;
        public $value;
        // Booleans for tracking activity.
        public $tuesday;
        public $wednesday;
        public $thursday;
        public $friday;
        public $saturday;
        public $sunday;
        public $monday;

        public function __construct() {
            $this->dbConnect();
            if ( isset($this->id) ) {
                $this->id = (int)$this->id;
                $this->track_date = date('D M d Y H:i:s O', strtotime($this->track_date));
                $this->weight = $this->roundFloat($this->weight);
                $this->value = $this->roundFloat($this->value);
                $this->formatDaysBool();
            }

        }

        public function __destruct() {
            $this->conn = null;
        }

        public function __toString() {
            return $this->weight;
        }

        public function formatDaysBool() {
            $this->tuesday = ($this->tuesday == true);
            $this->wednesday = ($this->wednesday == true);
            $this->thursday = ($this->thursday == true);
            $this->friday = ($this->friday == true);
            $this->saturday = ($this->saturday == true);
            $this->sunday = ($this->sunday == true);
            $this->monday = ($this->monday == true);
        }

        public static function roundFloat($float_value) {
            return round( (float)$float_value, 1 );
        }

        public function setWeight($weight) {
            $this->weight = $this->roundFloat($weight);
        }

        public function validate() {
            /*  Checks attributes for valid inputs. In the style of Backbone.js, this
             *  only returns a value if one or more attributes are incorrect.
             */
            if ( !isset($this->user, $this->track_date, $this->weight, $this->value) ) {
                return "One of more required fields is blank.";
            }
            if ($this->weight <= 0) {
                return "Reported weight must be a positive number.";
            }
        }

        public function save() {
            /*  If the object is valid, runs either an UPDATE or an INSERT query
             *  depending on the existence of the ID attribute. Upon INSERT, the
             *  new ID is attached to the object.
             */
            $not_valid = $this->validate();
            if ($not_valid) {
                throw new TrackingException($not_valid);
            }
            if ( isset($this->id) ) {
                $stmt = $this->conn->prepare("UPDATE track t SET t.track_date = :track_date, t.weight = :weight, t.value = :value WHERE t.id = :id");
                $stmt->execute(array(
                    "id"=>$this->id,
                    "track_date"=>$this->sanitizeDate($this->track_date),
                    "weight"=>$this->weight,
                    "value"=>$this->value
                ));
            } else {
                $stmt = $this->conn->prepare("INSERT INTO track (`user`, `track_date`, `weight`, `value`, `tuesday`, `wednesday`, `thursday`, `friday`, `saturday`, `sunday`, `monday`) VALUES (:user, :track_date, :weight, :value, :tuesday, :wednesday, :thursday, :friday, :saturday, :sunday, :monday);");
                $stmt->execute(array(
                    "user"=>(int)$this->user,
                    "track_date"=>$this->sanitizeDate($this->track_date),
                    "weight"=>$this->weight,
                    "value"=>$this->roundFloat($this->value),
                    "tuesday"=>(int)$this->tuesday,
                    "wednesday"=>(int)$this->wednesday,
                    "thursday"=>(int)$this->thursday,
                    "friday"=>(int)$this->friday,
                    "saturday"=>(int)$this->saturday,
                    "sunday"=>(int)$this->sunday,
                    "monday"=>(int)$this->monday
                ));
                $this->id = (int)$this->conn->lastInsertId();
            }
        }
    }
?>