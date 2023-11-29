<?php
    session_start();
    
    // TODO check whether the user is already logged in

    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');

    $dbManager = new dbManager();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if ($dbManager->loginUser($email, $password)) {
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