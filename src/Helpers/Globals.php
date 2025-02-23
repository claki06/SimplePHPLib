<?php

    use Framework\Helpers\Validator;
    use Framework\Helpers\Files;
    use Framework\Helpers\Linker;
    use Framework\Routing\Route;

    session_start();

    /**
     * Loads page from /app/Pages/x.page
     * @param string $page: page name
     * @param assoc[var] $data: assoc array of variables available inside page
     */
    function view($page, $data = null){

        header("Content-Type: text/html");

        if($data != null){
            extract($data);
        }

        $fileController = new Files();
        
        $linker = new Linker();
        
        $pagePath = $fileController->makePath("/app/Pages/$page" . ".php");

        if(file_exists($pagePath)){

            $page = $linker->link(file_get_contents($pagePath), $data, $page); 
            
            
            $page = eval("?>" . $page);

        }

        echo $page;

    }


    /**
     * Redirect user to given uri
     * @param string $uri: uri
     */
    function redirect($uri){
        header("Location: " . $uri);
        exit;
    }


    /**
     * Validates form data with conditions. Their keys are same
     * @param assoc[var] $data: data to validate
     * @param assoc[string] $conditions: conditions for data
     * @return assoc[var]: validated data
     */
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


    /**
     * Get last form data
     * @param string $oldValueName: name of form input;
     * @return var|false
     */
    function old($oldValueName){

        if(isset($_SESSION["POST_DATA"][$oldValueName])){
            return $_SESSION["POST_DATA"][$oldValueName];
        }else{
            return false;
        }
    }


    /**
     * Returns values of invalid form input
     * @param string $inputName: name of input form
     * @return var|false
     */
    function invalid($inputName){

        if(isset($_SESSION['VALIDATION_FAILS'][$inputName])){
            return $_SESSION['VALIDATION_FAILS'][$inputName];
        }
        else {
            return false;
        }

    }


    /**
     * Returns values of arguments from GET request
     * @param string $argumentName: argumentName
     * @return var
     */
    function get($argumentName){

        if(isset($_GET[$argumentName])){
            return $_GET[$argumentName];
        }else{
            exit();
        }

    }


    /**
     * Returns value from $_SESSION assoc array
     * @param var $key: assoc array key
     * @return var
     */
    function getSessionValue($key){
        return $_SESSION[$key];
    }


    /**
     * Sets value to $_SESSION assoc array
     * @param var $key: assoc array key
     * @param var $value: value for corresponding assoc array key
     */
    function setSessionValue($key, $value){
        $_SESSION[$key] = $value;
    }


    /**
     * Checks if request was made on mobile
     * @return int|false
     */
    function isMobile(){
        return Route::isMobile();
    }


    /**
     * Returns authenticated(logged in) user (assoc array | object)
     * @return assoc[var]|false
     */
    function auth(){
        if(isset($_SESSION["AUTH_USER"])){
            return $_SESSION["AUTH_USER"];
        }
        else {
            return false;
        }
        
    }


    /**
     * Saves user object | assoc array into $_SESSION["AUTH_USER"]
     * @param var $usrObj: user object | assoc array
     */
    function login($usrObj) {

        $_SESSION["AUTH_USER"] = $usrObj;
        
    }


    /**
     * It unsets user object | assoc array from $_SESSION["AUTH_USER"]
     */
    function logout(){
        unset($_SESSION["AUTH_USER"]);
    }


    /**
     * Returns latest get URI
     * @return string
     */
    function latestUri(){
        return $_SESSION["LATEST_GET_URI"];
    }

    function getBasePath(){

        $filleController = new Files();
        return $filleController->getBasePath();
    }

    function unsetSessionVariable($key){
        unset($_SESSION[$key]);
    }

?>