<?php
    session_start();
    
    // TODO check whether the user is already logged in

    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');

    $dbManager = new dbManager();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirmpwd = trim($_POST['confirmPwd']);

        if ($dbManager->registerUser($firstname, $lastname, $email, $password, $confirmpwd)) {
            $_SESSION['success'] = 'Registration successful, go to login page to access your content';
            header('Location: ../login.php');
            exit;
        }
        else { // invalid registration
            header('Location: ../registration.php');
            exit;
        }
    }
    else { // invalid request
        $_SESSION['error'] = 'Invalid request';
        header ('Location: ../registration.php');
        exit;
    }

?>