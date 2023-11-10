<?php

    namespace Framework\Helpers;

    class UserInputHandler{

        public static function dropTableInput($tableName){
            echo "\e[1mDo you want to drop table \e[90m$tableName\e[39m (yes/no)?\e[0m \n";
            echo "Input: ";
            list($userInput) = sscanf(trim(fgets(STDIN)), "%s");;

            return $userInput;
        }

    }

?>