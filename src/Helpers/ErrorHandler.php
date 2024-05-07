<?php

    namespace Framework\Helpers;

    class ErrorHandler{


        /**
         * @var assoc[string]: assoc array of error names 
         */
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

        
        /**
         * Prints error message of given command
         * @param string $message: error message
         */
        private static function TerminateWithMessage($message){
            echo "\e[31mError: \e[0m" . $message . "\n";
            exit();
        }


        /**
         * Stops program and opens error.php if error stops the execution of progrm
         */
        public function TerminateError(){

            $error = error_get_last();
            $errorType = $this->exceptions[$error['type']];

            if($error != null){

                if($error["type"] != 1 &&  $error["type"] != 4){
                    
                    return;
                    
                }
                
                require(__DIR__ . "/../Errors/error.php");
            }
            
        }


        /**
         * Initializes errors display
         */
        public static  function InitializeErrors(){
            $errorHandler = new ErrorHandler();
            ini_set( "display_errors", "off" );
            error_reporting(1);
            register_shutdown_function([$errorHandler, 'TerminateError']);
        }


        /**
         * Checks if column data satisfies validations defined in /app/Tables/table.php
         * @param string $tableName: table name
         * @param string $columnName: column name
         * @param string $propertiesString: property that is invalid
         */
        public static function ColumnValidationError($tableName, $columName, $propertiesString){
            
            $message = "Expression invalid in " . ucwords($tableName) . ".php: " . $columName . " => " . $propertiesString; 

            ErrorHandler::TerminateWithMessage($message);

        }


        /**
         * Prints error if table already exists in database
         */
        public static function tableAlreadyExistsError(){
            $message = "Table already exists in database";
            ErrorHandler::TerminateWithMessage($message);
        }


        /**
         * Prints error if table doesn't exist in database
         */
        public static function tableDoesntExistsError(){
            $message = "Table doesn't exists";
            ErrorHandler::TerminateWithMessage($message);
        }


        /**
         * Prints error if factory key doesn't exist
         */
        public static function factoryKeyDoesntExists($wrongKey){
            $message = "\e[90m'$wrongKey'\e[0m factory key doesn't exists";
            ErrorHandler::TerminateWithMessage($message);
        }


        /**
         * Prints error if /app/Downloads folder already exists
         */
        public static function downloadsExists(){
            $message = "\e[90mDownloads\e[0m folder already exists";
            ErrorHandler::TerminateWithMessage($message);
        }


        /**
         * Prints error if /app/Uploads folder already exists
         */
        public static function uploadsExist(){
            $message = "\e[90mUploads\e[0m folder already exists";
            ErrorHandler::TerminateWithMessage($message);
        }


    }
?>