<?php 
    require_once("/opt/lampp/htdocs/SAW/SAWFinalProject/php/scripts/errInitialize.php");
    require_once("/opt/lampp/htdocs/SAW/SAWFinalProject/php/phpClasses/dbManager.php");
    require_once("/opt/lampp/htdocs/SAW/SAWFinalProject/php/phpClasses/sessionManager.php");
    require_once("/opt/lampp/htdocs/SAW/SAWFinalProject/php/phpClasses/cookieManager.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();

    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);
?>