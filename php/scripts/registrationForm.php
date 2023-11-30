<?php
    session_start();
    
    // TODO check whether the user is already logged in

    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');

    require_once('../phpClasses/user.php');

    $dbManager = new dbManager();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        

        try { // we create the user here, if some parameters are invalid/empty we catch the exception
            $user = new User($_POST['firstName'], $_POST['lastName'], $_POST['userName'],
                             $_POST['email'], $_POST['password'], $_POST['confirmPwd'],
                             $_POST['gender'], $_POST['birthday'], false);
        }
        catch (Exception $e) {
            error_log($e->getMessage(), 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
            $_SESSION['error'] = $e->getMessage();
            header('Location: ../registration.php');
        }
        
        // TODO check whether the user is already registered
        if ($dbManager->registerUser($user)) {
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