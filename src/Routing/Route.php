<?php

    namespace Framework\Routing;

    use Exception;
    use Framework\Routing\Middleware;


    class Route{

        
        /**
         * Checks if HTTP request is GET request and executes callable or action 
         * if request URI matches with $route
         * @param string $route: URI
         * @param function|Controller $callable: function or controllable
         * @param string $action: function inside $callable controller
         */
        public static function get($route, $callable, $action = null){

            if($_SERVER["REQUEST_METHOD"] != "GET"){
                return;
            }

            if($argumentBeginning = strpos($_SERVER["REQUEST_URI"], "?")){
                
                $uri = substr($_SERVER["REQUEST_URI"],0, $argumentBeginning);
            }
            else{

                $uri = $_SERVER["REQUEST_URI"];

            }


            $uriParts = explode('/', $uri);
            array_shift($uriParts);

            $routeParts = explode('/', $route);
            array_shift($routeParts);

            $arguments = array();

            if(count($uriParts) != count($routeParts)){
                return;
            }

            if(preg_match("/\[\p{Any}+\]/", $route)){   
                            
                for($i = 0; $i < count($routeParts); $i++){


                    if(!preg_match("/\[\p{Any}+\]/", $routeParts[$i])){

                        if($uriParts[$i] == $routeParts[$i]){
                            continue;
                        }

                        return;

                    }


                    $argumentName = substr($routeParts[$i], 1, strlen($routeParts[$i]) - 2);

                    $routeParts[$i] = $uriParts[$i];
                    
                    $arguments[$argumentName] = $uriParts[$i];

                }

            }

            if($action == null && "/" . implode("/", $routeParts) == $uri){

                setSessionValue("LATEST_GET_URI", getSessionValue("CURRENT_GET_URI"));

                setSessionValue("CURRENT_GET_URI", $uri);

                Route::resetInvalidInput();

                $callable($arguments);

                exit();
            }


            if("/" . implode("/", $routeParts) == $uri){

               setSessionValue("LATEST_GET_URI", getSessionValue("CURRENT_GET_URI"));

               setSessionValue("CURRENT_GET_URI", $uri);

               Route::resetInvalidInput();

               $controller = new $callable();

               $controller->$action($arguments);

                exit();
            }

        }


        /**
         * Checks if HTTP request is POST request and executes callable or action 
         * if request URI matches with $route
         * @param string $route: URI
         * @param function|Controller $callable: function or controllable
         * @param string $action: function inside $callable controller
         */
        public static function post($route, $callable, $action = null ){

            
            if($_SERVER["REQUEST_METHOD"] != "POST"){
                return;
            }

            $uri = $_SERVER["REQUEST_URI"];


            if($action == null && $route == $uri){

                Route::checkCSRFToken();

                $callable($_POST);
                
                exit();
            }

            if($uri == $route){

                Route::checkCSRFToken();

                $controller = new $callable();

                $controller->$action($_POST);

                exit();
            }
        }


        /**
         * Groups Route::get and Route::post URIs and allows only users with 
         * access type privilages to access them
         * @param string $accessType: access privlage
         * @param function $callable: function with Route::get and Route:post 
         * for coresponding access type
         */
        public static function group($accessType, $callable, $isNot=false){

            $middleWare = new Middleware();

            if($isNot){
                if(!$middleWare->middleware($accessType)){
                    $callable();
                }
            }
            else{
                if($middleWare->middleware($accessType)){
                    $callable();
                }
            }
            



        }

        public static function put($route){

        }

        public static function delete($route){

        }


        /**
         * Checks if user is on computer
         * @param function $callback: function with Route::get and Route:post 
         * for computer
         */
        public static function computer($callback){

            if(!Route::isMobile()){

                $callback();

            }

        }


        /**
         * Checks if user is on phone
         * @param function $callback: function with Route::get and Route:post 
         * for phone
         */
        public static function phone($callback){
            if(Route::isMobile()){

                $callback();

            }
        }


        /**
         * Checks if user is on phone or not
         * @return int|false
         */
        public static function isMobile(){

            return preg_match('/^.*[mM]obile.*/',$_SERVER["HTTP_USER_AGENT"]);

        }


        /**
         * Checks if CSRF tokens match
         */
        private static function checkCSRFToken(){

            $bodyData = json_decode(file_get_contents("php://input"), true);
            
            if((getSessionValue("CSRFToken") == $_POST["CSRFToken"]) || (getSessionValue("CSRFToken") == $bodyData["CSRFToken"])){
                return;
            }else{
                redirect('/');
                exit();
            }
        }

        private static function resetInvalidInput(){
            if(getSessionValue("VALIDATION_FAILS") != null && getSessionValue("LATEST_GET_URI") != getSessionValue("CURRENT_GET_URI"))
            {
                setSessionValue("VALIDATION_FAILS", null);
            }
        }

    }



?>