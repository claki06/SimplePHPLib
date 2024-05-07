<?php

    /**
     * Loading autoload.php
     */
    require_once ("./../vendor/autoload.php");

    
    /**
     * Loading Globals.php
     */
    require ("./../src/Helpers/Globals.php");


    /**
     * Loading ErrorHandler.php
     */
    require(__DIR__ . "/ErrorHandler.php");

    use Dotenv\Dotenv;
    use Framework\Helpers\ErrorHandler;


    /**
     * Loading .env file
     */
    $dotenv = Dotenv::createImmutable(__DIR__ . "/../../");
    $dotenv->load();


    /**
     * Initializing ErrorHandler;
     */
    ErrorHandler::InitializeErrors();

?> 

