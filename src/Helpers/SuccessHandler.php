<?php

    namespace Framework\Helpers;

    class SuccessHandler{

        private static function successMessage($message){
            echo "\e[32mSuccess: \e[0m" . $message . " \n";
        }

        public static function migrationSucceeded($tablesToMigrate){

            foreach($tablesToMigrate as $tableName){
                $tableName = lcfirst($tableName);
                $message = "Table \e[90m$tableName\e[0m was successfully migrated";
                SuccessHandler::successMessage($message);
            }

        }

        public static function deleteSucceeeded($tablesToDelete){

            foreach($tablesToDelete as $tableName){
                $tableName = lcfirst($tableName);
                $message = "Table '\e[90m$tableName\e[0m' was successfully deleted";
                SuccessHandler::successMessage($message);
            }

        }

        public static function modelCreationSucceeded($modelsNames){
            foreach($modelsNames as $modelName){
                $message = "Model '\e[90m$modelName\e[0m' was successfullly created";
                SuccessHandler::successMessage($message);
            }
        }

        public static function factoryCreationSucceeded($factoriesNames){
            foreach($factoriesNames as $factoryName){
                $message = "Factory '\e[90m$factoryName\e[0m' was successfullly created";
                SuccessHandler::successMessage($message);
            }
        }

        public static function controllerCreationSucceeded($controllersNames){
            foreach($controllersNames as $controllerName){
                $message = "Controller '\e[90m$controllerName\e[0m'  was successfullly created";
                SuccessHandler::successMessage($message);
            }
        }

        public static function downlaodsCreated(){
            $message = "\e[90mDownloads\e[0m folder successfully created";
            SuccessHandler::successMessage($message);
        }

        public static function uploadsCreated(){
            $message = "\e[90mUploads\e[0m folder successfully created";
            SuccessHandler::successMessage($message);
        }

    }

?>