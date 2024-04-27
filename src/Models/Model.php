<?php

    namespace Framework\Models;

    use Framework\Query\DatabaseAccess;
    use Framework\Query\Builder;

    class Model{

        protected $hasManyRels = [];

        public function __get($name){

            if(isset($this->$name)){
                return $this->$name;
            }
            else{
                return $this->$name();
            }
        }

        public function __set($name, $value){
            $this->$name = $value;
        }

        protected function hasMany($class , $localKey, $foreignKey, $columns = null){

            $builder = Builder::getBuilder();

            return $builder->hasMany(get_called_class()::$table , $class::$table, $class ,$localKey, $foreignKey, $this->$localKey, $columns);
            
        }

        protected function belongsTo($class, $localKey, $foreignKey, $columns = null){
            
            $builder = Builder::getBuilder();

            return $builder->belongsTo(get_called_class()::$table, $class::$table, $class, $localKey, $foreignKey, $this->$localKey, $columns);

        }

        public static function executeQuery($query, $valuesToBind = array()){
            
            $builder = Builder::getBuilder();

            return $builder->bareQuery($query, $valuesToBind)->getNoModel();

        }

        public static function start($columnsArray = null){

            $builder = Builder::getBuilder();

            $builder->getModelQuery(get_called_class() ,get_called_class()::$table, $columnsArray);

            return $builder;
        }

        public static function create($colValues){
            
            $db = DatabaseAccess::getDB();
            $builder = Builder::getBuilder();

            $builder->create($colValues, get_called_class()::$table)->get();
            
            $model = new Model();

            foreach($colValues as $column => $value){
                $model->$column = $value;
            }

            return $model;
        }


    }

?>