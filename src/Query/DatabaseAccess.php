<?php


    namespace Framework\Query;

    use mysqli;
    use Exception;
    use Framework\Helpers\Arrays;

    class DatabaseAccess{


        /**
         * @var mysqli
         */
        private $mysqli;


        /**
         * @var string[]: columns to get
         */
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


        /**
         * Returns string array of tables in database
         * @param string $query: query to get tables
         * @return string[]
         */
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


        /**
         * Executes query without any retunr
         * @param string $query: query to execute
         */
        public function executeNoReturnQuery($query){

            $stmt = $this->mysqli->prepare($query);

            $stmt->execute();

        }


        /**
         * Returns rows as assoc array
         * @param string $query: query to execute
         * @return assoc[var]
         */
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

            if(count($usersData) == 1){
                return $usersData;
            }else{
                return $usersData;
            }

        }


        /**
         * Returns $stmt with resolved binds
         * @param mysqli_stmt $stmt: stmt to which to bind values
         * @return mysqli_stmt
         */
        public function resolveBinds($stmt, $colValues){
            $types = '';

            foreach($colValues as $value){

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


        /**
         * Adds $array to colValues
         * @param string[] $array: string array of columns to get
         */
        public function addToColValues($array){
            $this->colValues = array_merge($this->colValues, $array);
        }

    }


?>