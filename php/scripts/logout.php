<?php
    require_once("../phpClasses/sessionManager.php");
    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();

    $sessionManager->endSession();
    $cookieManager->deleteCookie("remMeCookie");

    header('Location: /SAW/SAWFinalProject/index.php');
    exit;
?>