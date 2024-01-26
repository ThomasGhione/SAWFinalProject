<?php 
    $root = "/chroot/home/S5311626/public_html/php";
    
    require_once("$root/shared/errInitialize.php");
    require_once("$root/phpClasses/dbManagerAdmin.php");
    require_once("$root/phpClasses/sessionManager.php");
    require_once("$root/phpClasses/cookieManager.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManagerAdmin();
    
    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) {
        $cookieData = $cookieManager->getCookie("remMeCookie");
        $dbManager->recoverSession($cookieData, $sessionManager);
    }
?>