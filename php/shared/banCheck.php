<?php

    $root = $_SERVER["DOCUMENT_ROOT"];

    if ($sessionManager->isSessionSet()) {
        try {   
            $email = $sessionManager->getEmail();    
            $dbManager->activateConn();
            if ($dbManager->isBanned($email)) {
                error_log("[" . date("Y-m-d H:i:s") . "] User is banned". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("You are banned. Please contact an admin if you think you didn't violate our terms and conditions");
            }
            $dbManager->closeConn();
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            $dbManager->closeConn();
            header("Location: /SAW/SAWFinalProject/php/scripts/logout.php");
            exit;
        }
    }
?>