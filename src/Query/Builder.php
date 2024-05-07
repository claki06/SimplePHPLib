<?php


    namespace Framework\Query;

    use Framework\Helpers\ErrorHandler;
    use Framework\Helpers\Files;
    use Framework\Query\DatabaseAccess;
    use Framework\Models\Model;

    class Builder{


        /**
         * @var Files
         */
        private $fileController;


        /**
         * @var string[]\string: query or queries to execute
         */
        private $queries;


        /**
         * @var DatabaseAccess
         */
        private $dbAccess;


        /**
         * @var string: model which we want to get
         */
        private $currentModel;



        public function __construct(){
            $this->fileController = new Files();
            $this->dbAccess = new DatabaseAccess();
        }


        /**
         * Created queries to create table in database
         * @param string[] $tablesToMigrate: string array of table names
         * @return Builder
         */
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


        /**
         * Resolves columns properties for current table with table keys
         * @param assoc[string] $columnsProperties: assoc array where key is column name
         * and value is column table keys
         * @param string $tableName: table name
         * @return string
         */
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


        /**
         * Creates query
         * @param assoc[string] $formatedColumnProperties: assoc array with keys as functionality part
         * and value value is formated table keys
         * @param string $tableName: name of table
         * @return string
         */
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


        /**
         * Makes queries to delete tables in database
         * @param string[] $tablesToDelete: string array of table names to delete
         * @return Builder
         */
        public function deleteTableQueries($tablesToDelete = null){

            $tableQueries = [];            

            foreach($tablesToDelete as $table){
                
                $lowerCaseTableName = strtolower($table);

                $tableQueries[] = "DROP TABLE $lowerCaseTableName";

            }

            $this->queries =  $tableQueries;
            return $this;
        }


        /**
         * Makes query to show all tables;
         * @return Builder
         */
        public function getAllTablesQuery(){
            $this->queries = "SHOW TABLES";
            return $this;
        }


        /**
         * Make bare query (programmer defined whole query)
         * @param string @query: query to execute
         * @param var[] $valuesToBind: array of values to bind to query
         * @return Builder
         */
        public function bareQuery($query, $valuesToBind){

            $this->queries = $query;
            $this->dbAccess->addToColValues($valuesToBind);
            return $this;
        }


        /**
         * Makes select query and defined model to create
         * @param string $model: model name
         * @param string $tableName: table name
         * @param string[] $columnsArray: array of columns to get
         */
        public function getModelQuery($model, $tableName, $columnsArray = null){

            $this->currentModel = $model;

            if($columnsArray == null){
                $columnsArray[] = '*'; 
            }

            $columnsString = '';

            $columnsString = implode(", ", $columnsArray);

            $this->queries = "SELECT $columnsString FROM $tableName ";
        }


        /**
         * Returns query of this Builder
         * @return string
         */
        public function getQuery(){
            return $this->queries;
        }


        /**
         * Adds where clause to query
         * @param string @whereQuery: where query part
         * @param var[] $valuesToBind: array of values to bind to query
         * @return Builder
         */
        public function where($whereQuery, $valuesToBind = null){

            $this->queries .= " WHERE " . $whereQuery;

            if(!is_null($valuesToBind)) $this->dbAccess->addToColValues($valuesToBind);

            return $this;
        }

        
        /**
         * @param string @andQuery: and query part
         * @param var[] $valuesToBind: array of values to bind to query
         * @return Builder
         */
        public function and($andQuery, $valuesToBind = null){

            $this->queries .= " AND " . $andQuery;

            if(!is_null($valuesToBind)) $this->dbAccess->addToColValues($valuesToBind);

            return $this;
        }


        /**
         * @param string @orQuery: or query part
         * @param var[] $valuesToBind: array of values to bind to query
         * @return Builder
         */
        public function or($orQuery, $valuesToBind = null){

            $this->queries .= " OR " . $orQuery;

            if(!is_null($valuesToBind)) $this->dbAccess->addToColValues($valuesToBind);

            return $this;
        }


        /**
         * @param assoc[var] $columnData: assoc array with columns as keys
         * and column values as value
         * @param string $tableName: table name
         * @return Builder
         */
        public function create($columnData, $tableName){

            $columns = implode(', ', array_keys($columnData));

            $values = [];
            
            for($i = 0; $i < count($columnData); $i++){
                $values[] = "?";
            }

            $values = implode(", ", $values);

            $this->queries = "INSERT INTO $tableName ($columns) VALUES ($values)";

            $this->dbAccess->addToColValues(array_values($columnData));
            
            return $this;
        }


        /**
         * Builds query for one to many relationship
         * @param string $leftTable: name of left table
         * @param string $rightTable: name of right table
         * @param string $model: namespace and model name /App/Models/x
         * @param string $leftKey: primary key of left table
         * @param string $rightKey: primary key of right table
         * @param var $identifier: id of current model
         * @param string[] $columns: columns to get from query
         * @return Build
         */
        public function hasMany($leftTable, $rightTable, $model, $leftKey, $rightKey, $identifier, $columns){

            $this->currentModel = $model;

            if($columns == null){
                $columns[] = '*'; 
            }

            $columnsString = implode(", ", $columns);

            $this->queries = "SELECT $rightTable.$columnsString FROM $leftTable INNER JOIN $rightTable ON $leftTable.$leftKey = $rightTable.$rightKey WHERE $leftTable.$leftKey = $identifier";

            return $this;
        }


        /**
         * Builds query for one to many relationship
         * @param string $leftTable: name of left table
         * @param string $rightTable: name of right table
         * @param string $model: namespace and model name /App/Models/x
         * @param string $leftKey: primary key of left table
         * @param string $rightKey: primary key of right table
         * @param var $identifier: value of primary key of current model
         * @param string[] $columns: columns to get from query
         * @return Build
         */
        public function belongsTo($leftTable, $rightTable, $model, $leftKey, $rightKey, $identifier, $columns){

            $this->currentModel = $model;

            if($columns == null){
                $columns[] = '*'; 
            }

            $columnsString = implode(", ", $columns);

            $this->queries = "SELECT $rightTable.$columnsString FROM $leftTable INNER JOIN $rightTable ON $leftTable.$leftKey = $rightTable.$rightKey WHERE $leftTable.$leftKey = $identifier LIMIT 1 ";

            return $this;
        }


        /**
         * Returns data in form of assoc array (no model)
         * @return assoc[var]
         */
        public function getNoModel(){
            $data = $this->dbAccess->getData($this->queries);

            if(!$data){
                return false;
            }

            return $data;
        }


        /**
         * Returns data in form of model
         * @param bool $singleRow: if true, returns only first row of data in form of model
         * otherwise it returns array of models
         */
        public function get($singleRow = false){

            $data = $this->dbAccess->getData($this->queries);

            if(!$data){
                return false;
            }

            $modelName = $this->currentModel;

            if(!$singleRow){

                $models = [];

                foreach($data as $row){

                    $model = new $modelName();

                    foreach($row as $column => $value){

                        $model->$column = $value;
                    }

                    $models[] = $model;

                }

                return $models;
            }
            else{

                $model = new $modelName();

                foreach($data[0] as $column => $value){

                    $model->$column = $value;
                }

                return $model;
            }
        }
    }

?>