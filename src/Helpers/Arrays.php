<?php
    namespace Framework\Helpers;

    class Arrays{
        public static function passArrayByRef($array){
            $refs = [];
            foreach ($array as $key => $value) {
                $refs[] = &$array[$key];
            }
            return $refs;
        }
    }


?>