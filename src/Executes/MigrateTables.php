<?php

    use Framework\Connection\Migration;
    use Framework\Query\Builder;
    use Framework\Query\DatabaseAccess;

    $migration = new Migration(new DatabaseAccess(), new Builder());

    if(count($argv) == 1){

        $migration->migrateTable();

    }else{

        array_shift($argv);

        $migration->migrateTable($argv);
    }


?>