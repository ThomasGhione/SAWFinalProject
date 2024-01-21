<?php
    require("../../shared/initializePageAdmin.php");
    
    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../../index.php");
        exit;
    }
    
    try {
        if (!isset($_GET["email"])) {
            error_log("Bad method used or email not set", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Bad method used or email not set");
        }

        $email = urldecode($_GET["email"]);

        if (empty($email)) {
            error_log("Email can't be empty", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Email can't be empty");
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
            error_log("Value inside link is not an email", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Value inside link isn't an email");
        }

        if ($email == $sessionManager->getEmail()) {
            error_log("You can't ban yourself while you're logged, please contact a technician to do so", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("You can't delete yourself while you're logged, please contact a technician to do so");
        }

        if ($dbManager->banUser($email))
            $_SESSION["success"] = "User banned successfully";
        
    }
    catch (Exception $e) { $_SESSION["error"] = $e->getMessage(); }

    unset($dbManager);
    header("Location: ../manageUsers.php");
    exit;

?>