<?php
    class PreSurveyException extends Exception {}


    class PreSurvey extends Model {
        public $id;
        protected $user;
        protected $age;
        protected $gender;
        protected $ethnicity;
        protected $zipcode;
        protected $county;
        protected $height;
        protected $weight;
        // bmi = (weight / height**2) * 703
        protected $bmi;
        // How many days per week do you currently engage in moderate physical activity for
        // a total of at least 30 minutes?
        public $q1;
        // Have you been trying to lose weight during the past 3 months?
        public $q2a;
        // Have you been losing weight?
        public $q2b;
        // How many pounds do you feel you need to lose in order to be at a healthy weight?
        public $q3;
        // Realistically, how many pounds would you like to lose during this 10-week challenge?
        public $q4;
        // On a scale of 1-10, with 1 being "highly unlikely" and 10 being "very likely",
        // how confident are you that you can lose weight?
        public $q5;

        public static $GENDER_OPTIONS = array("M", "F");
        public static $ETHNICITY_OPTIONS = array("WHITE", "BLACK", "HISPANIC", "ASIAN", "NATIVE AMERICAN",
            "OTHER", "NA");
        public static $COUNTY_OPTIONS = array("NCCO", "KCO", "SCO", "OUT");

        public function __construct() {
            $this->dbConnect();
        }

        public static function calcBMI($weight, $height) {
            return ($weight * 703) / pow($height, 2);
        }

        private function setBMI() {
            if ( $this->weight && $this->height ) {
                $this->bmi = self::calcBMI($this->weight, $this->height);
            }
        }

        public function setUser(User $user) {
            $this->user = $user->id;
        }

        public function setAge($age) {
            $this->age = (int)$age;
        }

        public function setGender($gender) {
            $this->gender =  in_array($gender, self::$GENDER_OPTIONS) ? $gender : null;
        }

        public function setEthnicity($ethnicity) {
            $this->ethnicity = in_array($ethnicity, self::$ETHNICITY_OPTIONS) ? $ethnicity : null;
        }

        public function setCounty($county) {
            $this->county = in_array($county, self::$COUNTY_OPTIONS) ? $county : null;
        }

        public function setZipCode($zipcode) {
            if ( strlen($zipcode) == 5 ) {
                $this->zipcode = $zipcode;
            }
        }

        public function setWeight($weight) {
            $this->weight = round((float)$weight, 1);
            $this->setBMI();
        }

        public function setHeight($height) {
            $this->height = (int)$height;
            $this->setBMI();
        }

        public function validate() {
            if ( !isset($this->age, $this->gender, $this->ethnicity, $this->county, $this->zipcode, $this->height, $this->weight, $this->q1, $this->q2a, $this->q3, $this->q4, $this->q5) ) {
                echo json_encode($this);
                return "One or more fields is missing or incorrect.";
            }
            if (!isset($this->user)) {
                return "User not registered.";
            }

            if ( (int)$this->q2a == 1 && !isset($this->q2b) ) {
                return "Please answer the incomplete questions below.";
            }
        }

        public function save() {
            $not_valid = $this->validate();
            if ($not_valid) {
                throw new PreSurveyException($not_valid);
            }
            if ( isset($this->id) ) {
                $stmt = $this->conn->prepare("UPDATE survey s SET s.user = :user, s.age = :age, s.gender = :gender, s.ethnicity = :ethnicity, s.zipcode = :zipcode, s.county = :county, s.heigh = :height, s.weight = :weight, s.bmi = :bmi, s.q1 = :q1, s.q2a = :q2a, s.q2b = :q2b, s.q3 = :q3, s.q4 = :q4, s.q5 = :q5 WHERE s.id = :id;");
                $stmt->execute(array(
                    "id"=>(int)$this->id,
                    "user"=>(int)$this->user,
                    "age"=>(int)$this->age,
                    "gender"=>$this->gender,
                    "ethnicity"=>$this->ethnicity,
                    "zipcode"=>$this->zipcode,
                    "county"=>$this->county,
                    "height"=>$this->height,
                    "weight"=>$this->weight,
                    "bmi"=>$this->bmi,
                    "q1"=>(int)$this->q1,
                    "q2a"=>(int)$this->q2a,
                    "q2b"=>$this->q2b ? (int)$this->q2b : null,
                    "q3"=>$this->q3,
                    "q4"=>(int)$this->q4,
                    "q5"=>(int)$this->q5
                ));
            } else {
                $stmt = $this->conn->prepare("INSERT INTO survey (`user`, `age`, `gender`, `ethnicity`, `zipcode`, `county`, `height`, `weight`, `bmi`, `q1`, `q2a`, `q2b`, `q3`, `q4`, `q5`) VALUES (:user, :age, :gender, :ethnicity, :zipcode, :county, :height, :weight, :bmi, :q1, :q2a, :q2b, :q3, :q4, :q5);");
                $stmt->execute(array(
                    "user"=>(int)$this->user,
                    "age"=>(int)$this->age,
                    "gender"=>$this->gender,
                    "ethnicity"=>$this->ethnicity,
                    "zipcode"=>$this->zipcode,
                    "county"=>$this->county,
                    "height"=>$this->height,
                    "weight"=>$this->weight,
                    "bmi"=>$this->bmi,
                    "q1"=>(int)$this->q1,
                    "q2a"=>(int)$this->q2a,
                    "q2b"=>$this->q2b ? (int)$this->q2b : null,
                    "q3"=>$this->q3,
                    "q4"=>(int)$this->q4,
                    "q5"=>(int)$this->q5
                ));
                $this->id = $this->conn->lastInsertId();
            }
        }

    }
?>