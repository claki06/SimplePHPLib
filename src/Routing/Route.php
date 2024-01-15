<?php

    namespace Framework\Routing;

    use Framework\Routing\Middleware;


    class Route{

        

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

                $_SESSION["LATEST_GET_URI"] = $uri;
                
                $callable($arguments);

                exit();
            }


            if("/" . implode("/", $routeParts) == $uri){

               $_SESSION["LATEST_GET_URI"] = $uri;

               $controller = new $callable();

               $controller->$action($arguments);

                exit();
            }

        }



        public static function post($route, $callable, $action = null ){
            
            if($_SERVER["REQUEST_METHOD"] != "POST"){
                return;
            }

            $uri = $_SERVER["REQUEST_URI"];

            if($action == null && $route == $uri){
                $callable($_POST);
                exit();
            }

            if($uri == $route){

                $controller = new $callable();

                $controller->$action($_POST);

                exit();
            }
        }

        public static function group($accessType, $callable){

            $middleWare = new Middleware();

            $middleWare->middleware($accessType);

            $callable();

        }

        public static function put($route){

        }

        public static function delete($route){

        }

        public static function computer($callback){

            if(!Route::isMobile()){

                $callback();

            }

        }

        public static function phone($callback){
            if(Route::isMobile()){

                $callback();

            }
        }

        public static function isMobile(){

            return preg_match('/^.*[mM]obile.*/',$_SERVER["HTTP_USER_AGENT"]);

        }

    }



?>