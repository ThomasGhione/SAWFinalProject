<?php
    require("../php/shared/initializePage.php");    

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../index.php");
        exit;
    }

    if ($cookieManager->isCookieSet("remMeCookie")) {  
        $cookieData = $cookieManager->getCookie("remMeCookie");  
        $dbManager->deleteRememberMeCookieFromDB($cookieData, $sessionManager->getEmail());
        $cookieManager->deleteCookie("remMeCookie");
    }
    
    $sessionManager->endSession();
    
    header("Location: ../index.php");
    exit;
?>