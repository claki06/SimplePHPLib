<?php

    namespace Framework\Routing;


    class Middleware{


        /**
         * Checks access type and corresponding reaction
         * @param string $accessType: type of access
         */
        public function middleware($accessType){

            switch($accessType){
                case "auth":
                    $this->auth();
            }

        }


        /**
         * Checks if user is authenticated and it redirects 
         * him if he isn't
         */
        private function auth() {

            if(!auth()){
                redirect($_SESSION["LATEST_GET_URI"]);
            }

        }
    }

?>