<?php

    use Framework\Helpers\Files;

    array_shift($argv);

    $factoriesPath = "App\\Database\\Factories\\";


    if(count($argv) == 0){

        $fileController = new Files();

        foreach($fileController->readDir("/app/Database/Factories") as $factoryName ){

            $specificFactory = $factoriesPath . $factoryName;
    
            $factory = new $specificFactory();
    
            $factory->create();

        };

    }
    else{

        foreach($argv as $argument){
        
            $specificFactory = $factoriesPath . $argument;
    
            $factory = new $specificFactory();
    
            $factory->create();
    
        }
    }
    

?>