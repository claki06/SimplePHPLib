<?php

    use Framework\Helpers\Files;
    use Framework\Helpers\SuccessHandler;

    $fileController = new Files();

    array_shift($argv);

    $arguments = $argv;

    foreach($arguments as $argument){
        $fileController->writeModelTemplateFile($argument);
    }

    SuccessHandler::modelCreationSucceeded($arguments);

?>