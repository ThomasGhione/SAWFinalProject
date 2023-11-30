<?php
    session_start();
    
    // TODO check whether the user is already logged in

    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');

    $dbManager = new dbManager();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        try {
            $user = new User(true, $_POST['email'], $_POST['password']);
        }
        catch (Exception $e) {
            error_log($e->getMessage(), 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
            $_SESSION['error'] = $e->getMessage();
            header('Location: ../registration.php');
        }

        if ($dbManager->loginUser($user)) {
            header('Location: ../personalArea.php');
            exit;
        }
        else { // invalid login
            header('Location: ../login.php');
            exit;
        }
    }
    else { // invalid request
        $_SESSION['error'] = 'Invalid request';
        header('Location: ../login.php');
        exit;
    }

?>