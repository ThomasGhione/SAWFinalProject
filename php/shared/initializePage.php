<?php 
    $root = $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/php";
    
    require_once("$root/shared/errInitialize.php");
    require_once("$root/phpClasses/dbManager.php");
    require_once("$root/phpClasses/dbManagerAdmin.php");
    require_once("$root/phpClasses/sessionManager.php");
    require_once("$root/phpClasses/cookieManager.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();
    $dbManagerAdmin = new dbManagerAdmin();

    
    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);

    if ($sessionManager->isSessionSet()) {
        try {   
            $email = $sessionManager->getEmail();        
            if ($dbManager->isBanned($email)) {
                error_log("User is banned", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("You are banned. Please contact an admin if you think you didn't violate our terms and conditions");
            }    
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../scripts/logout.php");
            exit;
        }
    }
?>