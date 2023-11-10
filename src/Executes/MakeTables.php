<?php

    use Framework\Helpers\Files;

    array_shift($argv);

    $fileController = new Files();

    foreach($argv as $argument){
        
        foreach($fileController->readDir('/app/Database/Tables') as $tabeName){
            if($argument == $tabeName){
                echo "This table already Exists. Please enter tables that doesn't exist. \n";
                exit();
            }
        }


        $fileController->writeTableTemplateFile($argument);

    }

?>