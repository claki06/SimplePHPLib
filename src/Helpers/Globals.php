<?php

    use Framework\Helpers\Validator;
    use Framework\Helpers\Files;
    use Framework\Helpers\Linker;
    use Framework\Routing\Route;

    session_start();

    function view($page, $data = null){


        if($data != null){
            extract($data);
        }

        $fileController = new Files();
        
        $linker = new Linker();
        
        $pagePath = $fileController->makePath("/app/Pages/$page" . ".php");

        if(file_exists($pagePath)){

            $page = $linker->link(file_get_contents($pagePath), $data, $page); 

            $page = eval("?>" . $page . "<?php ");

        }



    }

    function redirect($url, $data = null){
        header("Location: " . $url);
    }

    function validate($data, $conditions){

        $_SESSION["POST_DATA"] = $_POST;

        $validator = new Validator();

        foreach($conditions as $key => $value){

            $validator->validateKey($key, $data[$key], $value);

        }

        $validator->redirectIfNotPassed();

        unset($_SESSION["POST_DATA"]);
        unset($_SESSION["VALIDATION_FAILS"]);

        return $validator->getData();

    }

    function old($oldValueName){

        if(isset($_SESSION["POST_DATA"][$oldValueName])){
            return $_SESSION["POST_DATA"][$oldValueName];
        }else{
            return false;
        }
    }

    function invalid($inputName){

        if(isset($_SESSION['VALIDATION_FAILS'][$inputName])){
            return $_SESSION['VALIDATION_FAILS'][$inputName];
        }
        else {
            return false;
        }

    }

    function get($argumentName){

        if(isset($_GET[$argumentName])){
            return $_GET[$argumentName];
        }else{
            exit();
        }

    }

    function getSessionValue($argumentName){
        return $_SESSION[$argumentName];
    }

    function setSessionValue($argumentName, $value){
        $_SESSION[$argumentName] = $value;
    }

    function isMobile(){
        return Route::isMobile();
    }

    function auth(){
        if(isset($_SESSION["AUTH_USER"])){
            return $_SESSION["AUTH_USER"];
        }
        else {
            return false;
        }
        
    }

    function login($usrObj) {

        $_SESSION["AUTH_USER"] = $usrObj;
        
    }

    function logout(){
        unset($_SESSION["AUTH_USER"]);
    }

    function latestUri(){
        return $_SESSION["LATEST_GET_URI"];
    }


?>