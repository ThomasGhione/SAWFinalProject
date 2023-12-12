<?php
    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');
    require_once("../phpClasses/user.php");
    
    $sessionManager = new sessionManager();

    // TODO Code to check if cookie is set
    if ( $sessionManager->isSessionSet() ) {
        header('Location: ../personalArea.php');
        exit;
    }

    $dbManager = new dbManager();

    if ($_SESSION["serverStatus"] == 'POST') {
        unset($_SESSION["serverStatus"]);

        try {
            $user = new User(true);
            unset($_SESSION["postData"]);
        }
        catch (Exception $e) {
            error_log($e->getMessage(), 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
            $_SESSION['error'] = $e->getMessage();
            header('Location: ../registration.php');
        }

        if ($dbManager->loginUser($user)) {
            // Code to set session and cookie if remember me is set
            
            $sessionManager->setSessionVariables($user->getEmail(), $user->getPermission());

            // TODO code for rememberMe Cookie

            header('Location: ../personalArea.php');
            exit;
        }
    }
    else { // invalid request
        $_SESSION['error'] = 'Invalid request';
    }

    // Covers both invalid request and invalid login 
    header('Location: ../login.php');
    exit;

?>