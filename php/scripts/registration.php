<?php 
    require_once("./errInitialize.php");
    require_once('../phpClasses/dbManager.php');
    require_once("../phpClasses/cookieManager.php");
    require_once('../phpClasses/sessionManager.php');
    require_once('../phpClasses/user.php');

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();
    
    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);

    if ($sessionManager->isSessionSet()) {
        header('Location: ../personalArea.php');
        exit;
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $user = new User(false);        

        // TODO check whether the user is already registered
        if ($dbManager->registerUser($user)) {
            header('Location: ../loginForm.php');
            exit;
        }
        else { // invalid registration
            header('Location: ../registrationForm.php');
            exit;
        }
    }
    else { // invalid request
        
        // TODO Al posto di usare solo un errore per l'utente, restituire anche un log_error
        $_SESSION['error'] = 'Something went wrong, please retry later';
        header ('Location: ../registrationForm.php');
        exit;
    }

?>