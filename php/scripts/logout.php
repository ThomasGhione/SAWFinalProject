<?php
    require_once("../phpClasses/sessionManager.php");
    require_once("../phpClasses/cookieManager.php");
    require_once("../phpClasses/dbManager.php");
    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();

    if ($cookieManager->isCookieSet("remMeCookie")) {
        $dbManager->deleteRememberMeCookieFromDB($cookieManager->getCookie("remMeCookie"), $sessionManager->getEmail());
        $cookieManager->deleteCookie("remMeCookie");
    }
    
    $sessionManager->endSession();


    header('Location: /SAW/SAWFinalProject/index.php');
    exit;
?>