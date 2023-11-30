<?php 
    session_start();

    require_once('./phpClasses/dbManager.php');
    require_once('./phpClasses/sessionManager.php');

    $dbManager = new dbManager();

    if ($data = $dbManager->allUsers()) {
        
    }
    else () {

    }
?>