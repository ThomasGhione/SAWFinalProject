<?php 
    $root = "/chroot/home/S5311626/public_html/php";

    require_once("$root/shared/errInitialize.php");
    require_once("$root/phpClasses/dbManager.php");
    require_once("$root/phpClasses/sessionManager.php");
    require_once("$root/phpClasses/cookieManager.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();
    
    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) {
        $cookieData = $cookieManager->getCookie("remMeCookie");
        $dbManager->recoverSession($cookieData, $sessionManager);
    }
?>