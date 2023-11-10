<?php

    namespace Framework\Helpers;

    class WarningHandler{

        private static function printWarning($message){
            echo "\e[33mWarning: \e[0m" . $message . "\n";
        }

        public static function tableExistsWarning($tableName){
            $message = "table \e[90m$tableName\e[0m already exists in database";
            WarningHandler::printWarning($message);
        }

    }

?>