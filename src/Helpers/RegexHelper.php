<?php

    namespace Framework\Helpers;

    class RegexHelper{

        /**
         * Replaces $ with \\$ and \ with \/ and returns a string
         * @param string $string: pattern with symbols to replace
         * @return string
         */
        public static function preparePattern($string){

            $string = str_replace("$", "\\$", $string);
            $string = str_replace("/", "\/", $string);

            return $string;

        }

    }

?>