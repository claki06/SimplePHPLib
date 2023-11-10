<?php

    use Framework\Helpers\Files;
    use Framework\Helpers\SuccessHandler;

    $fileController = new Files();

    array_shift($argv);

    foreach($argv as $argument){

        $fileController->writeFactoryTemplate($argument);

    }

    SuccessHandler::factoryCreationSucceeded($argv);


?>