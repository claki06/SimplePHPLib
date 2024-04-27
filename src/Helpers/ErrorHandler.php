<?php

    namespace Framework\Helpers;

    class ErrorHandler{

        private $exceptions = [
            E_ERROR => "E_ERROR",
            E_WARNING => "E_WARNING",
            E_PARSE => "E_PARSE",
            E_NOTICE => "E_NOTICE",
            E_CORE_ERROR => "E_CORE_ERROR",
            E_CORE_WARNING => "E_CORE_WARNING",
            E_COMPILE_ERROR => "E_COMPILE_ERROR",
            E_COMPILE_WARNING => "E_COMPILE_WARNING",
            E_USER_ERROR => "E_USER_ERROR",
            E_USER_WARNING => "E_USER_WARNING",
            E_USER_NOTICE => "E_USER_NOTICE",
            E_STRICT => "E_STRICT",
            E_RECOVERABLE_ERROR => "E_RECOVERABLE_ERROR",
            E_DEPRECATED => "E_DEPRECATED",
            E_USER_DEPRECATED => "E_USER_DEPRECATED",
            E_ALL => "E_ALL"
        ];

        

        private static function TerminateWithMessage($message){
            echo "\e[31mError: \e[0m" . $message . "\n";
            exit();
        }

        private function TerminateError(){

            $error = error_get_last();
            $errorType = $this->exceptions[$error['type']];

            if($error != null){

                if($error["type"] != 1 &&  $error["type"] != 4){
                    
                    return;
                    
                }
                
                require(__DIR__ . "/../Errors/error.php");
            }
            
        }

        public static  function InitializeErrors(){
            $errorHandler = new ErrorHandler();
            ini_set( "display_errors", "off" );
            error_reporting(1);
            register_shutdown_function([$errorHandler, 'TerminateError']);
        }

        public static function ColumnValidationError($tableName, $columName, $propertiesString){
            
            $message = "Expression invalid in " . ucwords($tableName) . ".php: " . $columName . " => " . $propertiesString; 

            ErrorHandler::TerminateWithMessage($message);

        }

        public static function tableAlreadyExistsError(){
            $message = "Table already exists in database";
            ErrorHandler::TerminateWithMessage($message);
        }

        public static function tableDoesntExistsError(){
            $message = "Table doesn't exists";
            ErrorHandler::TerminateWithMessage($message);
        }

        public static function factoryKeyDoesntExists($wrongKey){
            $message = "\e[90m'$wrongKey'\e[0m factory key doesn't exists";
            ErrorHandler::TerminateWithMessage($message);
        }

        public static function downloadsExists(){
            $message = "\e[90mDownloads\e[0m folder already exists";
            ErrorHandler::TerminateWithMessage($message);
        }

        public static function uploadsExist(){
            $message = "\e[90mUploads\e[0m folder already exists";
            ErrorHandler::TerminateWithMessage($message);
        }


    }
?>