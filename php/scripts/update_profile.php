<?php
    require("../shared/initializePage.php");    

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../loginForm.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] != "POST") 
        $_SESSION["error"] = "Invalid request";
    else { // Following code checks the number of arguments used in POST, it can be improved... probably :3
        $emptyCount = 0;
            
        foreach ($_POST as $dataName => $data) 
            if (!empty($_POST[$dataName])) ++$count;

        try {
            
            // We used a count because it's much easier to expand the profile editing with more options
            if ($count < 2) {
                error_log("User must choose at least 1 field to edit", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Please choose at least 1 field to edit, number of empty values = $count");
            }
            if ($count > 4) {
                error_log("Invalid request", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Invalid request");
            }
            if (!$dbManager->editProfile($sessionManager->getEmail(), $sessionManager)) {
                error_log("Something went wrong while editing user", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Something went wrong while editing user. Please try again later");
            }
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../update_profile_form.php");
            exit;
        }

        $_SESSION["success"] = "Your changes were applied successfully!";
    }    
    
    header("Location: ../update_profile_form.php"); // Covers both invalid request and invalid login 
    exit;

?>