<?php
    require("../../shared/initializePageAdmin.php");
    
    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../../index.php");
        exit;
    }

    
    try {
        if (!isset($_GET["email"])) {
            error_log("[" . date("Y-m-d H:i:s") . "] Bad method used or email not set". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
            throw new Exception("Bad method used or email not set");
        }

        $email = urldecode($_GET["email"]);

        if (empty($email)) {
            error_log("[" . date("Y-m-d H:i:s") . "] Email can't be empty". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
            throw new Exception("Email can't be empty");
        }

        if (filter_var($email, FILTER_VALIDATE_EMAIL) === FALSE) {
            error_log("[" . date("Y-m-d H:i:s") . "] Value inside link is not an email". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
            throw new Exception("Value inside link isn't an email");
        }

        if ($email == $sessionManager->getEmail()) {
            error_log("[" . date("Y-m-d H:i:s") . "] You can't delete yourself while you're logged, please contact a technician to do so". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
            throw new Exception("You can't delete yourself while you're logged, please contact a technician to do so");
        }

        if ($dbManager->deleteUser($email))
            $_SESSION["success"] = "User deleted successfully";
        
    }
    catch (Exception $e) { $_SESSION["error"] = $e->getMessage(); }

    header("Location: ../manageUsers.php");
    exit;

?>