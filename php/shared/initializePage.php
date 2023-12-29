<?php 
    $root = $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/php";
    
    require_once("$root/scripts/errInitialize.php");
    require_once("$root/phpClasses/dbManager.php");
    require_once("$root/phpClasses/sessionManager.php");
    require_once("$root/phpClasses/cookieManager.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();

    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);
?>