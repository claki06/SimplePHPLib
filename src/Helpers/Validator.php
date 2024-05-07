<?php

    namespace Framework\Helpers;

    class Validator{


        /**
         * @var assoc[var]: validated data
         */
        private $validatedData = array();
        

        /**
         * @var assoc[string]: error messages for form inputs that
         * dont meet requirements 
         */
        private $errorMessages = array();


        /**
         * checks if form input meets requirements for one condition
         * @param string $key: form input name
         * @param var $data: data given in form input
         * @param string $condition: condition for current form input
         */
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


        /**
         * Checks current form input with conditions
         * @param string $key: form input name
         * @param var $userInput: form input value
         * @param string $conditionsString: string of conditions for current form input
         */
        public function validateKey($key, $userInput, $conditionsString){

            $conditions = explode("|",$conditionsString);

            foreach($conditions as $condition){

                $this->checkInput($key ,$userInput, $condition);

            }

        }


        /**
         * If there are form inputs that didn't pass validation
         * redirects user to last get URI
         * @param string $url: url to redirect user if validation fails
         */
        public function redirectIfNotPassed($url = null){


            if(!empty($this->errorMessages)){

                $_SESSION["VALIDATION_FAILS"] = $this->errorMessages;

                if($url == null){
                    
                    redirect($_SESSION["LATEST_GET_URI"]);

                    exit();
                }
                else{
                    redirect($url);
                    exit();
                }

            }

        }


        /**
         * Returns data that passed the validation
         * @return assoc[var]
         */
        public function getData(){

            return $this->validatedData;

        }

    }

?>