<?php

    require_once ("./../vendor/autoload.php");

    require ("./../src/Helpers/Globals.php");

    require(__DIR__ . "/ErrorHandler.php");

    use Dotenv\Dotenv;
    use Framework\Helpers\ErrorHandler;

    $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
    
    $dotenv->load();

    ErrorHandler::InitializeErrors();

?> 

