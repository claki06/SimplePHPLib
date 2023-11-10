<?php

    namespace Framework\Connection;

    use Framework\Query\DatabaseAccess;
    use Framework\Query\Builder;
    use Framework\Helpers\SuccessHandler;


    class Delete{

        private $dbAccess;
        private $queryBuilder;

        public function __construct(){
            $this->dbAccess = new DatabaseAccess();
            $this->queryBuilder = new Builder();
        }

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