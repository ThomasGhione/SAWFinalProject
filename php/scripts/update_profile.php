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

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../loginForm.php");
        exit;
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // TODO Servirà un try-catch

        $string = "";

        if ($dbManager->editUser()) 
            $_SESSION["success"] = "Your changes were applied successfully!";
        else 
            $_SESSION["error"] = "Something went wrong, try again now, if the problem persists contact admin";
    }    
    else // invalid request
        $_SESSION["error"] = "Invalid request";
    
    header("Location: ../update_profile_form.php"); // Covers both invalid request and invalid login 
    exit;

?>