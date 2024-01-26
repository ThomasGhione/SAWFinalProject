<?php
    require("../shared/initializePage.php");    

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../loginForm.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD" != "POST"])
        $_SESSION["error"] = "Invalid request";
    else {
        try {
            if (!isset($_POST["submit"]) || !isset($_POST["oldPassword"]) || !isset($_POST["newPassword"])){
                error_log("[" . date("Y-m-d H:i:s") . "] Invalid request". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("Invalid request");
            }

            if (empty($_POST["oldPassword"]) || (empty($_POST["newPassword"]))) {
                error_log("[" . date("Y-m-d H:i:s") . "] At least one of the fields in update_password is empty". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("No fields can be empty");
            }

            if ($dbManager->updatePassword($sessionManager->getEmail())) 
                $_SESSION["success"] = "Your changes were applied successfully"; 
        }
        catch (Exception $e) { $_SESSION["error"] = $e->getMessage(); }
    }

    header("Location: ../update_profile_password_form.php"); // Covers both invalid request and invalid login 
    exit;

?>