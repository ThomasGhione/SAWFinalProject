<?php 
    require_once("./errInitialize.php");
    require_once("../phpClasses/dbManager.php");
    require_once("../phpClasses/cookieManager.php");
    require_once("../phpClasses/sessionManager.php");
    require_once("../phpClasses/user.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();
    
    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);

    if ($sessionManager->isSessionSet()) {
        header("Location: ../show_profile.php");
        exit;
    }

    try {
        if ($_SERVER["REQUEST_METHOD"] != "POST") { // invalid request
            error_log("Invalid request", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Something went wrong, please try again later");
        }         
        
        $user = new User(false);        

        // TODO check whether the user is already registered

        header("Location: " .($dbManager->registerUser($user)
            ? "../loginForm.php"         // valid registration
            : "../registrationForm.php") // invalid registration
        );
        exit;
    }
    catch (Exception $e) { // invalid request
        $_SESSION["error"] = $e->getMessage();
        header("Location: ../registrationForm.php");
        exit;
    }

?>