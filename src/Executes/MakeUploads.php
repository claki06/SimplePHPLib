<?php
    use Framework\Helpers\Files;
    use Framework\Helpers\ErrorHandler;
    use Framework\Helpers\SuccessHandler;

    $fileController = new Files();

    if(!file_exists($fileController->makePath("/app/Uploads"))){
        
        mkdir($fileController->makePath("/app/Uploads"));

        SuccessHandler::uploadsCreated();
    }
    else{

        ErrorHandler::uploadsExist();

    }

?>