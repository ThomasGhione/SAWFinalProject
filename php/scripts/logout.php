<?php
    require_once("./errInitialize.php");
    require_once("../phpClasses/sessionManager.php");
    require_once("../phpClasses/cookieManager.php");
    require_once("../phpClasses/dbManager.php");
    
    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();

    if ( !$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);

    if ( !$sessionManager->isSessionSet() ) {
        header("Location: ../../index.php");
        exit;
    }

    
    if ($cookieManager->isCookieSet("remMeCookie")) {    
        $dbManager = new dbManager();
        $dbManager->deleteRememberMeCookieFromDB($cookieManager->getCookie("remMeCookie"), $sessionManager->getEmail());
        $cookieManager->deleteCookie("remMeCookie");
    }
    
    $sessionManager->endSession();
    

    header("Location: /SAW/SAWFinalProject/index.php");
    exit;
?>