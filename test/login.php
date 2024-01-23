<?php
    include("../php/scripts/login.php");
    
    /*require("../php/shared/initializePage.php");  

    require_once("../php/phpClasses/user.php");

    if ($sessionManager->isSessionSet()) {
        header("Location: ./show_profile.php");
        exit;
    }


    if ($_SERVER["REQUEST_METHOD"] != "POST")  
        $_SESSION["error"] = "Invalid request";
    else {
        
        $user = new User(true);

        if ($dbManager->loginUser($user)) { // if remember me is set, then following code sets session and cookie
            
            $sessionManager->setSessionVariables($user->getEmail(), $user->getPermission(), $user->getNewsletter());

            header("Location: ./show_profile.php");
            exit;
        }
    }
    
    header("Location: ../php/loginForm.php"); // Covers both invalid request and invalid login 
    exit;
    */
?>