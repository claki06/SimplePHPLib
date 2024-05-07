<?php

    namespace Framework\Helpers;

    class WarningHandler{


        /**
         * Prints warning message in console
         * @param string $message: message to print in console
         */
        private static function printWarning($message){
            echo "\e[33mWarning: \e[0m" . $message . "\n";
        }


        /**
         * Prints table that already exists in database
         * @param string $tableName: name of table that exists in database
         */
        public static function tableExistsWarning($tableName){
            $message = "table \e[90m$tableName\e[0m already exists in database";
            WarningHandler::printWarning($message);
        }

    }

?>