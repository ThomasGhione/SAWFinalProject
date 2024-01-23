<?php
    require($_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/php/shared/initializePage.php");    

    if (!$sessionManager->isSessionSet()) {
        header("Location: /SAW/SAWFinalProject/index.php");
        exit;
    }

    if ($cookieManager->isCookieSet("remMeCookie")) {  
        $cookieData = $cookieManager->getCookie("remMeCookie");  
        $dbManager->deleteRememberMeCookieFromDB($cookieData, $sessionManager->getEmail());
        $cookieManager->deleteCookie("remMeCookie");
    }
    
    $sessionManager->endSession();
    
    header("Location: /SAW/SAWFinalProject/index.php");
    exit;
?>