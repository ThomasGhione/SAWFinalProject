<?php
    require("../../shared/initializePage.php");
    
    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../index.php");
        exit;
    }

    require_once("../../phpClasses/dbManagerAdmin.php");
    $dbManagerAdmin = new dbManagerAdmin();



    
    if (isset($_GET["email"])) {
        $email = urldecode($_GET["email"]);

        if (filter_var($email, FILTER_VALIDATE_EMAIL)){
            
            if ($email == $sessionManager->getEmail()) {
                $_SESSION["error"] = "You can't delete yourself while you're logged, please contact a technician to do so";
            }
            elseif ($dbManagerAdmin->deleteUser($email)) {
                $_SESSION["success"] = "User successfully deleted";
            } 
        }
        else {
            $_SESSION["error"] = "Value in link is not an email";
        }
    }
    else {
        $_SESSION["error"] = "Bad method used or email not set";
    }

    header("Location: ../manageUsers.php");
    exit;

?>