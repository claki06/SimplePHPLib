<?php

    use Framework\Connection\Delete;

    $delete = new Delete();

    if(count($argv) == 1){

        $delete->deleteTables();

    }else{

        array_shift($argv);
        
        $delete->deleteTables($argv);

    }

?>