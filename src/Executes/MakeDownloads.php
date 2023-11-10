<?php

    use Framework\Helpers\Files;
    use Framework\Helpers\ErrorHandler;
    use Framework\Helpers\SuccessHandler;

    $fileController = new Files();

    if(!file_exists($fileController->makePath("/app/Downloads"))){
        
        mkdir($fileController->makePath("/app/Downloads"));

        SuccessHandler::downlaodsCreated();
    }
    else{

        ErrorHandler::downloadsExists();

    }

?>