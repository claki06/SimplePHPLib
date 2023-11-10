<?php

    use Framework\Helpers\Files;
    use Framework\Helpers\SuccessHandler;

    array_shift($argv);

    $fileController = new Files();

    foreach($argv as $argument){
        $fileController->writeControllerTemplate($argument);
    }

    SuccessHandler::controllerCreationSucceeded($argv);

?>