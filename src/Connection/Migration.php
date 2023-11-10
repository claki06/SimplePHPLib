<?php

    namespace Framework\Connection;

    use Framework\Helpers\ErrorHandler;
    use Framework\Helpers\SuccessHandler;
    use Framework\Helpers\Files;
    use Framework\Helpers\UserInputHandler;
    use Framework\Helpers\WarningHandler;
    use Framework\Query\Builder;
    use Framework\Query\DatabaseAccess;

    class Migration{

        private $dbAccess;
        private $queryBuilder;
        private $fileController;

        public function __construct(){
            $this->dbAccess = new DatabaseAccess();
            $this->queryBuilder = new Builder();
            $this->fileController = new Files();
        }

        private function deleteTableIfExists($tablesToMigrate){

            $tablesInDB = $this->dbAccess->executeGetAllTablesQuery($this->queryBuilder->getAllTablesQuery()->getQuery());
            $tablesToDelete = [];

            if($tablesToMigrate == null){

                $tablesToMigrate = $this->fileController->readDir('/app/Database/Tables');

            }

            foreach($tablesToMigrate as $tableName){

                foreach($tablesInDB as $tableInDB){

                    if(lcfirst($tableName) == $tableInDB){
                        
                        WarningHandler::tableExistsWarning($tableInDB);
                        
                        $userInput = UserInputHandler::dropTableInput($tableInDB);
                        
                        if($userInput != "yes"){
                            ErrorHandler::tableAlreadyExistsError();

                        }else{
                            $tablesToDelete[] = $tableInDB;                              
                        }

                    }

                }
                
            }

            $deleteTablesQueries = $this->queryBuilder->deleteTableQueries($tablesToDelete)->getQuery();
            
            foreach($deleteTablesQueries as $deleteTablesQuery){
                $this->dbAccess->executeNoReturnQuery($deleteTablesQuery);
            }

        }

        public function migrateTable($tablesToMigrate = null){  
            
            $this->deleteTableIfExists($tablesToMigrate);

            $queries = $this->queryBuilder->buildTableQueries($tablesToMigrate)->getQuery();

            foreach($queries as $query){
                $this->dbAccess->executeNoReturnQuery($query);
            }

            if($tablesToMigrate == null){
                SuccessHandler::migrationSucceeded($this->fileController->readDir('/app/Database/Tables'));
            }else{
                SuccessHandler::migrationSucceeded($tablesToMigrate);
            }
            
        }

    }

?>