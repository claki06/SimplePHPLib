<?php

    namespace Framework\Helpers;

    class Validator{

        private $validatedData = array();
        
        private $errorMessages = array();



        private function checkInput($key, $data, $condition){


            switch($condition){

                case preg_match("/^min(.*)$/", $condition) == 1:
                    
                    $numberOfChars = substr( $condition ,strpos($condition, ":") + 1);
                    if(!is_numeric($numberOfChars)){
                        return;
                    }

                    if(strlen($data) < $numberOfChars){
                        $this->errorMessages[$key] = "Minimum number of charcters is " . $numberOfChars;
                        break;
                    }else{
                        $this->validatedData[$key] = $data;
                        break;
                    }

                case preg_match("/^max(.*)$/", $condition) == 1:

                    $numberOfChars = substr( $condition ,strpos($condition, ":") + 1);
                    
                    if(!is_numeric($numberOfChars)){
                        return;
                    }

                    if(strlen($data) > $numberOfChars){
                        $this->errorMessages[$key] = "Maximum number of charcters is " . $numberOfChars;
                        break;
                    }else{
                        $this->validatedData[$key] = $data;
                        break;
                    }
                case 'email':

                    if(filter_var($data, FILTER_VALIDATE_EMAIL)){
                        $this->validatedData[$key] = $data;
                    }else{
                        $this->errorMessages[$key] = "Invalid email address";
                        break;
                    }
                case 'notEmpty':
                    if(strlen($data) == 0){
                        $this->errorMessages[$key] = "Field must not be empty";
                    }
                    else{
                        $this->validatedData[$key] = $data;
                    }

            }

        }

        public function validateKey($key, $userInput, $conditionsString){

            $conditions = explode("|",$conditionsString);

            foreach($conditions as $condition){

                $this->checkInput($key ,$userInput, $condition);

            }

        }

        public function redirectIfNotPassed($url = null){


            if(!empty($this->errorMessages)){

                $_SESSION["VALIDATION_FAILS"] = $this->errorMessages;

                if($url == null){
                    
                    redirect($_SESSION["LATEST_GET_URI"]);

                    exit();
                }

            }

        }

        public function getData(){


            return $this->validatedData;

            // if(empty($this->errorMessages)){
            //     return $this->validatedData;
            // }else{
            //     return $this->errorMessages;
            // }

        }

    }

?>