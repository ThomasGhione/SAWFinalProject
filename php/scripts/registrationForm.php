<?php 
    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');
    require_once('../phpClasses/user.php');

    $sessionManager = new sessionManager();
    
    // TODO Code to check if cookie is set
    if ( $sessionManager->isSessionSet() ) {
        header('Location: ../personalArea.php');
        exit;
    }

    $dbManager = new dbManager();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        try { // we create the user here, if some parameters are invalid/empty we catch the exception
            $user = new User(false, $_POST['email'], $_POST['password'],
                             $_POST['firstName'], $_POST['lastName'], $_POST['userName'],
                             $_POST['confirmPwd'], $_POST['gender'], $_POST['birthday'],);
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