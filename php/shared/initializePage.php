<?php 
    $root = $_SERVER["DOCUMENT_ROOT"];
    
    require_once("$root/SAW/SAWFinalProject/php/scripts/errInitialize.php");
    require_once("$root/SAW/SAWFinalProject/php/phpClasses/dbManager.php");
    require_once("$root/SAW/SAWFinalProject/php/phpClasses/sessionManager.php");
    require_once("$root/SAW/SAWFinalProject/php/phpClasses/cookieManager.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();

    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);
?>