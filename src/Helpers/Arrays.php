<?php
    namespace Framework\Helpers;

    class Arrays{

        
        /**
         * Returns array of references of another array
         * @param var[] $array
         * @return &var[] 
         */
        public static function passArrayByRef($array){
            $refs = [];
            foreach ($array as $key => $value) {
                $refs[] = &$array[$key];
            }
            return $refs;
        }
    }


?>