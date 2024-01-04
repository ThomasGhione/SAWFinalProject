<?php
    require("../shared/initializePage.php");    

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../loginForm.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] != "POST") 
        $_SESSION["error"] = "Invalid request";
    else { 
        try {

            if (!isset($_POST["oldPassword"]) || !isset($_POST["newPassword"])){
                // TODO Gestire caso errore
            }

            if (empty($_POST["oldPassword"]) || (empty($_POST["newPassword"]))) {
                // TODO Gestire caso errore
            }

            if (!$dbManager->updatePassword($sessionManager->getEmail())) {
                error_log("Something went wrong while updating password", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Something went wrong while updating password. Please try again later");
            }
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../update_password_form.php");
            exit;
        }

        $_SESSION["success"] = "Your changes were applied successfully!";
    }    
    
    header("Location: ../update_password_form.php"); // Covers both invalid request and invalid login 
    exit;

?>