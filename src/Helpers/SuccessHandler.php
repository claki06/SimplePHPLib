<?php

    namespace Framework\Helpers;

    class SuccessHandler{


        /**
         * Prints success message in console
         * @param string $message: message to display in console
         */
        private static function successMessage($message){
            echo "\e[32mSuccess: \e[0m" . $message . " \n";
        }


        /**
         * Prints all tables migrated
         * @param string[] $tablesToMigrate: array of table names migrated
         */
        public static function migrationSucceeded($tablesToMigrate){

            foreach($tablesToMigrate as $tableName){
                $tableName = lcfirst($tableName);
                $message = "Table \e[90m$tableName\e[0m was successfully migrated";
                SuccessHandler::successMessage($message);
            }

        }


        /**
         * Prints all tables that are deleted
         * @param string[] $tablesToDelete: array to table names deleted
         */
        public static function deleteSucceeeded($tablesToDelete){

            foreach($tablesToDelete as $tableName){
                $tableName = lcfirst($tableName);
                $message = "Table '\e[90m$tableName\e[0m' was successfully deleted";
                SuccessHandler::successMessage($message);
            }

        }


        /**
         * Prints all models created
         * @param string[] $modelsNames: array of model names created
         */
        public static function modelCreationSucceeded($modelsNames){
            foreach($modelsNames as $modelName){
                $message = "Model '\e[90m$modelName\e[0m' was successfullly created";
                SuccessHandler::successMessage($message);
            }
        }


        /**
         * Prints all factories created
         * @param string[] $factoriesNames: array of facotry names created
         */
        public static function factoryCreationSucceeded($factoriesNames){
            foreach($factoriesNames as $factoryName){
                $message = "Factory '\e[90m$factoryName\e[0m' was successfullly created";
                SuccessHandler::successMessage($message);
            }
        }


        /**
         * Prints all controllers created
         * @param string[] $controllersNames: array of controllers names created
         */
        public static function controllerCreationSucceeded($controllersNames){
            foreach($controllersNames as $controllerName){
                $message = "Controller '\e[90m$controllerName\e[0m'  was successfullly created";
                SuccessHandler::successMessage($message);
            }
        }


        /**
         * Prints that downloads folder is created
         */
        public static function downlaodsCreated(){
            $message = "\e[90mDownloads\e[0m folder successfully created";
            SuccessHandler::successMessage($message);
        }


        /**
         * Prints that uploads folder is created
         */
        public static function uploadsCreated(){
            $message = "\e[90mUploads\e[0m folder successfully created";
            SuccessHandler::successMessage($message);
        }

    }

?>