<?php
    require($_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/php/shared/initializePage.php");

    require_once($_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/php/phpClasses/user.php");

    if ($sessionManager->isSessionSet()) {
        header("Location: /SAW/SAWFinalProject/php/show_profile.php");
        exit;
    }


    if ($_SERVER["REQUEST_METHOD"] != "POST")  
        $_SESSION["error"] = "Invalid request";
    else {
        
        $user = new User(true);

        if ($dbManager->loginUser($user)) { // if remember me is set, then following code sets session and cookie
            
            $sessionManager->setSessionVariables($user->getEmail(), $user->getPermission(), $user->getNewsletter());

            header("Location: /SAW/SAWFinalProject/php/show_profile.php");
            exit;
        }
    }
    
    header("Location: /SAW/SAWFinalProject/php/loginForm.php"); // Covers both invalid request and invalid login 
    exit;

?>