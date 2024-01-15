<?php


    namespace Framework\Query;

    use Framework\Helpers\ErrorHandler;
    use Framework\Helpers\Files;
    use Framework\Query\DatabaseAccess;
    use Framework\Models\Model;

    class Builder{

        private $fileController;

        private $queries;

        private static $builderInstace = null;

        private $dbAccess;

        private $currentModel;

        public function __construct(){
            $this->fileController = new Files();
            $this->dbAccess = DatabaseAccess::getDB();
        }

        public static function getBuilder(){
            if(self::$builderInstace == null){
                self::$builderInstace = new self();
            }

            return self::$builderInstace;
        }

        public function buildTableQueries($tablesToMigrate = null){

            $tableQueries = [];
            
            if($tablesToMigrate == null){
                $tablesToMigrate = $this->fileController->readDir('/app/Database/Tables');
            }

            foreach($tablesToMigrate as $table){

                $tableFileName = $this->fileController->addExtension($table);

                $columnProperties = require($this->fileController->makePath("/app/Database/Tables/" . $tableFileName));

                $tableQueries[] = $this->resolveTableColumnProperties($columnProperties, lcfirst($table));
            }

            $this->queries = $tableQueries;

            return $this;
        }


        private function resolveTableColumnProperties($columnProperties, $tableName){

            $formatedColumnProperties = [];
            
            foreach($columnProperties as $columnName => $propertiesString){

                $currentTypeArr = [];

                foreach(explode("|" , $propertiesString) as $property){
                    
                    $currentTypeArr["name"] = $columnName;

                    switch ($property){

                        case "int":
                            $currentTypeArr["type"] = "int";
                            break;
                        case "varchar":
                            $currentTypeArr["type"] = "varchar (255)";
                            break;
                        case "text":
                            $currentTypeArr['type'] = "TEXT";
                            break;
                        case "key":
                            $currentTypeArr["key"] = "PRIMARY KEY($columnName)";
                            break;
                        case "auto_increment":
                            $currentTypeArr["auto_increment"] = "AUTO_INCREMENT";
                            break;
                        case "nullable":
                            $currentTypeArr["nullable"] = "NULL";
                            break;

                        default:
                            ErrorHandler::ColumnValidationError($tableName, $columnName, $propertiesString);
                            
                    }

                }

                $formatedColumnProperties[] = $currentTypeArr;

            }

            return $this->makeColumnsSql($formatedColumnProperties, $tableName);

        }


        private function makeColumnsSql($formatedColumnProperties, $tableName){

            $sql = "";

            $properties = [];
            $additionalProps = [];

            foreach($formatedColumnProperties as $formatedColumnProperty){
                    
                $currentProperties = [];

                if(isset($formatedColumnProperty['name'])){
                    $currentProperties[] = $formatedColumnProperty['name'];
                }

                if(isset($formatedColumnProperty['type'])){
                    $currentProperties[] = $formatedColumnProperty['type'];
                }

                if(isset($formatedColumnProperty['nullable'])){
                    $currentProperties[] = $formatedColumnProperty['nullable'];
                }else{
                    $currentProperties[] = "NOT NULL";
                }

                if(isset($formatedColumnProperty['auto_increment'])){
                    $currentProperties[] = $formatedColumnProperty['auto_increment'];
                }

                if(isset($formatedColumnProperty['key'])){
                    $additionalProps[] = $formatedColumnProperty['key'];
                }
                
                $properties[] = implode(" ", $currentProperties);
            }

            $properties = array_merge($properties, $additionalProps);

            $sql = implode(", ", $properties);

            return "CREATE TABLE $tableName ($sql)";
        }

        public function deleteTableQueries($tablesToDelete = null){

            $tableQueries = [];
            $tables = $tablesToDelete;
            

            foreach($tables as $table){
                
                $lowerCaseTableName = lcfirst($table);

                $tableQueries[] = "DROP TABLE $lowerCaseTableName";

            }

            $this->queries =  $tableQueries;
            return $this;
        }

        public function getAllTablesQuery(){
            $this->queries = "SHOW TABLES";
            return $this;
        }

        public function getModelQuery($model, $tableName, $columnsArray = null){

            $this->currentModel = $model;

            if($columnsArray == null){
                $columnsArray[] = '*'; 
            }

            $columnsString = '';

            $columnsString = implode(", ", $columnsArray);

            $this->queries = "SELECT $columnsString FROM $tableName ";
        }

        public function getQuery(){
            return $this->queries;
        }

        public function where($columnName, $operator, $value){

            if(gettype($value) == "string"){
                $this->queries .= "WHERE $columnName $operator '$value' ";
            }else{
                $this->queries .= "WHERE $columnName $operator $value ";
            }

            return $this;
        }

        public function and($columnName, $operator, $value){

            if(gettype($value) == "string"){
                $this->queries .= "AND $columnName $operator '$value' ";
            }else{
                $this->queries .= "AND $columnName $operator $value ";
            }

            return $this;
        }

        public function or($columnName, $operator, $value){

            if(gettype($value) == "string"){
                $this->queries .= "OR $columnName $operator '$value' ";
            }else{
                $this->queries .= "OR $columnName $operator $value ";
            }

            return $this;
        }

        public function create($colValues, $tableName){

            $columns = implode(', ', array_keys($colValues));

            $values = [];
            
            for($i = 0; $i < count($colValues); $i++){
                $values[] = "?";
            }

            $values = implode(", ", $values);

            $query = "INSERT INTO $tableName ($columns) VALUES ($values)";
            
            return $query;
        }

        public function hasMany($leftTable, $rightTable, $model, $leftKey, $rightKey, $identifier){

            $this->currentModel = $model;

            $this->queries = "SELECT $rightTable.* FROM $leftTable INNER JOIN $rightTable ON $leftTable.$leftKey = $rightTable.$rightKey WHERE $leftTable.$leftKey = $identifier ";

            return $this->get();
        }

        public function belongsTo($leftTable, $rightTable, $model, $leftKey, $rightKey, $identifier){

            $this->currentModel = $model;

            $this->queries = "SELECT $rightTable.* FROM $leftTable INNER JOIN $rightTable ON $leftTable.$leftKey = $rightTable.$rightKey WHERE $leftTable.$leftKey = $identifier LIMIT 1 ";

            return $this->get(true);
        }

        public function get($returnArr = false){

            $data = $this->dbAccess->getData($this->queries);

            $models = [];

            $modelName = $this->currentModel;

            // if($returnArr){

            //     $model = new $modelName();

            //     foreach($data[0] as $column => $value){

            //         $model->$column = $value;

            //     }

            //     return $model;
            // }


            foreach($data as $user){

                $model = new $modelName();

                foreach($user as $column => $value){

                    $model->$column = $value;
                }

                $models[] = $model;

            }

            return $models;

        }

    }

?>