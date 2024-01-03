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
?>