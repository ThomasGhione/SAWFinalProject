<?php

    $root = $_SERVER["DOCUMENT_ROOT"];

    if ($sessionManager->isSessionSet()) {
        try {   
            $email = $sessionManager->getEmail();        
            if ($dbManager->isBanned($email)) {
                error_log("User is banned", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("You are banned. Please contact an admin if you think you didn't violate our terms and conditions");
            }    
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: $root/SAW/SAWFinalProject/php/scripts/logout.php");
            exit;
        }
    }
?>