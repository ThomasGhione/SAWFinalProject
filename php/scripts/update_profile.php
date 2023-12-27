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

        // Following code checks number of arguments used in POST, probabily it can be improved
        $count = 0;
            
        foreach ($_POST as $dataName => $data) 
            if (empty($data)) ++$count;
        
        if ($count < 2) {
            $_SESSION["error"] = "Choose at least one field to edit";
            return false;
        }

        if ($count > 4) {
            $_SESSION["error"] = "Invalid request";
            return false;
        }


        if ($dbManager->editUser($sessionManager->getEmail(), $sessionManager))
            $_SESSION["success"] = "Your changes were applied successfully!";
    }    
    else // invalid request
        $_SESSION["error"] = "Invalid request";
    
    header("Location: ../update_profile_form.php"); // Covers both invalid request and invalid login 
    exit;

?>