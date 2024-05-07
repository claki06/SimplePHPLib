<?php

    namespace Framework\Connection;

    use Framework\Query\DatabaseAccess;
    use Framework\Query\Builder;
    use Framework\Helpers\SuccessHandler;


    class Delete{


        /**
         * @var DatabaseAccess
         */
        private $dbAccess;


        /**
         * @var Builder
         */
        private $queryBuilder;


        public function __construct(){
            $this->dbAccess = new DatabaseAccess();
            $this->queryBuilder = new Builder();
        }


        /**
         * Deletes tables in database defined in .enf file
         * @param string[] $tablesToDelete: array of tables to delete
         */
        public function deleteTables($tablesToDelete = null){

            $queries = [];

            if($tablesToDelete == null){
                $getAllTablesQuery = $this->queryBuilder->getAllTablesQuery()->getQuery();
                $tablesToDelete = $this->dbAccess->executeGetAllTablesQuery($getAllTablesQuery);
            }

            $queries = $this->queryBuilder->deleteTableQueries($tablesToDelete)->getQuery();
            
            

            foreach($queries as $query){

                $this->dbAccess->executeNoReturnQuery($query);
                
            }

            SuccessHandler::deleteSucceeeded($tablesToDelete);
            
        }

    }

?>