<?php

    namespace Framework\Helpers;

    class RegexHelper{

        public static function preparePattern($string){

            $string = str_replace("$", "\\$", $string);
            $string = str_replace("/", "\/", $string);

            return $string;

        }

    }

?>