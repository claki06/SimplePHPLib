<?php

    ini_set('display_errors', 1);

    require_once ("./../vendor/autoload.php");

    require ("./../src/Helpers/Globals.php");

    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__ . "/../");
    
    $dotenv->load();

    require("./../app/Routes/Web.php");
    
?>