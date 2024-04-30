<?php


    namespace Framework\Query;

    use mysqli;
    use Exception;
    use Framework\Helpers\Arrays;

    class DatabaseAccess{

        private $mysqli;

        private static $dbInstace = null;

        private $colValues = array(); 

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

            if(substr_count($query, '?')){
                $stmt = $this->resolveBinds($stmt, $this->colValues);
            }

            $stmt->execute();

            $mysqliResult = $stmt->get_result();

            if(!$mysqliResult){
                return false;
            }

            $usersData = [];

            while($row = $mysqliResult->fetch_assoc()){

                $currentUserData = [];
                
                foreach($row as $key => $value){
                    $currentUserData[$key] = $value;
                }

                $usersData[] = $currentUserData;

            }


            $this->resetAccess();

            if(count($usersData) == 1){
                return $usersData;
            }else{
                return $usersData;
            }

        }

        public function resolveBinds($stmt, $colValues){
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


            call_user_func_array(array($stmt, 'bind_param'), Arrays::passArrayByRef($combinedArray));

            
            
            return $stmt;
        }

        public function addToColValues($array){
            $this->colValues = array_merge($this->colValues, $array);
        }

        private function resetAccess(){
            $this->colValues = array();
        }

    }


?>