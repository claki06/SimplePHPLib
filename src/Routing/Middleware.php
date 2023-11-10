<?php

    namespace Framework\Routing;


    class Middleware{

        public function middleware($accessType){

            switch($accessType){
                case "auth":
                    $this->auth();
            }

        }

        private function auth() {

            if(!auth()){
                redirect($_SESSION["LATEST_GET_URI"]);
            }

        }



    }

?>