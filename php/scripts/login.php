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
        header("Location: ../personalArea.php");
        exit;
    }

    $dbManager = new dbManager();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $user = new User(true);

        if ($dbManager->loginUser($user)) { // if remember me is set the following code sets session and cookie
            
            $sessionManager->setSessionVariables($user->getEmail(), $user->getPermission());

            // TODO code for rememberMe Cookie

            header("Location: ../personalArea.php");
            exit;
        }
    }
    else // invalid request
        $_SESSION["error"] = "Invalid request";
    
        
    header("Location: ../loginForm.php"); // Covers both invalid request and invalid login 
    exit;

?>