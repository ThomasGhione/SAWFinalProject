<?php
    require_once("../phpClasses/sessionManager.php");
    $sessionManager = new sessionManager();

    $sessionManager->startSession();

    $sessionManager->endSession();

    header('Location: /SAW/SAWFinalProject/index.php');
    exit;
?>