<?php
    session_start();
    
    // TODO check whether the user is already logged in

    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');

    $dbManager = new dbManager();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $firstname = trim($_POST['firstName']);
        $lastname = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirmpwd = trim($_POST['confirmPwd']);
        $userName = trim($_POST['userName']);

        // Optional params
        $gender = $_POST['gender'];
        $birthdate = isset($_POST['birthdate']) ? trim($_POST['birthdate']): null;

        if ($dbManager->registerUser($firstname, $lastname, $email, $password, $confirmpwd, $userName, $gender, $birthdate)) {
            header('Location: ../login.php');
            exit;
        }
        else { // invalid registration
            header('Location: ../registration.php');
            exit;
        }
    }
    else { // invalid request
        
        // TODO Al posto di usare solo un errore per l'utente, restituire anche un log_error
        $_SESSION['error'] = 'Something went wrong, please retry later';
        header ('Location: ../registration.php');
        exit;
    }

?>