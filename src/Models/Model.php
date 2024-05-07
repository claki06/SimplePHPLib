<?php

    namespace Framework\Models;

    use Framework\Query\DatabaseAccess;
    use Framework\Query\Builder;

    class Model{


        /**
         * If model has filed $name than it will return this field, otherwise
         * it will call the function with name $name
         * @param string $name: name of field or function
         * @return var
         */
        public function __get($name){

            if(isset($this->$name)){
                return $this->$name;
            }
            else{
                return $this->$name();
            }
        }


        /**
         * Setting field to object
         * @param string $name: field name
         * @param var $value: field value
         */
        public function __set($name, $value){
            $this->$name = $value;
        }


        /**
         * Returns a Builder instance made for one to many relationship
         * MODEL MUST HAVE PRIMARY KEY VALUE RETRIEVED
         * @param string $class: namespace and class name /App/Model/x.php
         * @param string $localKey: primary key of this model
         * @param string $foreignKey: foreign key of model we want to get
         * @param string[] $columns: array of column names you want to get
         * @return Builder
         */
        protected function hasMany($class , $localKey, $foreignKey, $columns = null){

            $builder = new Builder();

            return $builder->hasMany(get_called_class()::$table , $class::$table, $class ,$localKey, $foreignKey, $this->$localKey, $columns);
            
        }


        /**
         * Returns a Builder instance made for one to one relationship
         * MODEL MUST HAVE PRIMARY KEY VALUE RETRIEVED
         * @param string $class: namespace and class name /App/Model/x.php
         * @param string $localKey: primary key of this model
         * @param string $foreignKey: foreign key of model we want to get
         * @return Builder
         */
        protected function belongsTo($class, $localKey, $foreignKey, $columns = null){
            
            $builder = new Builder();

            return $builder->belongsTo(get_called_class()::$table, $class::$table, $class, $localKey, $foreignKey, $this->$localKey, $columns);

        }


        /**
         * Executes custom query and returns assoc array with columns as keys and columns values
         * as values
         * @param string $query: custom query
         * @param var[] $valuesToBind: values to bind to query (?)
         * @return assoc[var]
         */
        public static function executeQuery($query, $valuesToBind = array()){
            
            $builder = new Builder();

            return $builder->bareQuery($query, $valuesToBind)->getNoModel();

        }


        /**
         * Returns a Builder instace made with beginning syntax (SELECT * FROM x)
         * @param string[] $columnsArray: string array of columns to get
         * @return Builder
         */
        public static function start($columnsArray = null){

            $builder = new Builder();

            $builder->getModelQuery(get_called_class() ,get_called_class()::$table, $columnsArray);

            return $builder;
        }


        /**
         * Creates a new record in database of current model
         * @param assoc[var] $colValues: assoc array which keys are columns and 
         * values column value
         * @return var: created model
         */
        public static function create($colValues){
            
            $db = new DatabaseAccess();
            $builder = new Builder();

            $builder->create($colValues, get_called_class()::$table)->get();
            
            $model = new Model();

            foreach($colValues as $column => $value){
                $model->$column = $value;
            }

            return $model;
        }

    }

?>