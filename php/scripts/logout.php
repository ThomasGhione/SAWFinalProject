<?php
    require("../shared/initializePage.php");    

    if (!$sessionManager->isSessionSet()) {
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