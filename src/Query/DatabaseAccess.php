<?php


    namespace Framework\Query;

    use mysqli;
    use Exception;
    use Framework\Helpers\ErrorHandler;

    class DatabaseAccess{

        private $mysqli;

        private static $dbInstace = null;

        public function __construct(){

            try {
                $this->mysqli = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
            } catch (Exception $e) {
                $e->getMessage();
            }
            
            if($this->mysqli->connect_errno){
                echo "Connection Error: " . $this->mysqli->connect_error;
            }

        }

        public static function getDB(){
            if(self::$dbInstace == null){
                self::$dbInstace = new self();
            }
            return self::$dbInstace;
        }

        public function executeNoReturnQuery($query){
            
            $stmt = $this->mysqli->prepare($query);
            
            $stmt->execute();

        }

        public function executeGetAllTablesQuery($query){

            $stmt = $this->mysqli->prepare($query);

            $stmt->execute();

            $mysqliResult = $stmt->get_result();

            $results = [];
            
            while($row = $mysqliResult->fetch_assoc()){
                $results[] = $row['Tables_in_' . $_ENV["DB_NAME"]];
            }

            return $results;

        }

        public function getData($query = null){
            
            $stmt = $this->mysqli->prepare($query);

            $stmt->execute();

            $mysqliResult = $stmt->get_result();

            $usersData = [];

            while($row = $mysqliResult->fetch_assoc()){

                $currentUserData = [];
                
                foreach($row as $key => $value){
                    $currentUserData[$key] = $value;
                }

                $usersData[] = $currentUserData;

            }

            if(count($usersData) == 1){
                return $usersData;
            }else{
                return $usersData;
            }

        }

        public function createRecord($query, $colValues){
            
            $types = '';

            foreach($colValues as $key => $value){

                if(is_int($value)){
                    $types .= 'i';
                }
                
                if(is_float($value) || is_double($value)){
                    $types .= 'd';
                }

                if(is_string($value)){
                    $types .= 's';
                }

            }

            $combinedArray = array_merge(array($types), $colValues);

            if($stmt = $this->mysqli->prepare($query)){

            call_user_func_array(array($stmt, 'bind_param'), $this->passArrayByRef($combinedArray));

            $stmt->execute();

            }else{
                ErrorHandler::tableDoesntExistsError();
            }
                      
              

        }

        public function passArrayByRef($array){
            $refs = [];
            foreach ($array as $key => $value) {
                $refs[] = &$array[$key];
            }
            return $refs;
        }

    }


?>