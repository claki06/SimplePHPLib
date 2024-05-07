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

        /**
         * @var DatabaseAccess
         */
        private $dbAccess;


        /**
         * @var Builder
         */
        private $queryBuilder;


        /**
         * @var Files
         */
        private $fileController;

        public function __construct(){
            $this->dbAccess = new DatabaseAccess();
            $this->queryBuilder = new Builder();
            $this->fileController = new Files();
        }


        /**
         * Checks all tables in database defined in .env and compares them with tables
         * to migrate and if they have same name it will ask user if he wants to
         * delete table that is already in database.
         * @param string[] $tablesToMigrate: array of tables names to migrate
         */
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


        /**
         * Migrates tables to database
         * @param string[] $tablesToMigrate: array of tables names to migrate
         */
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