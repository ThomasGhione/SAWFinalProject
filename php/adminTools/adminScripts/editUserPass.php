<?php    
    require("../../shared/initializePageAdmin.php");    

    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../../index.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] != "POST") 
        $_SESSION["error"] = "Invalid request";
    else { // Following code checks the number of arguments used in POST, it can be improved... probably :3
        try {
        
            if (!isset($_POST["submit"]) || !isset($_POST["userEmail"]) || empty($_POST["userEmail"]) || !isset($_POST["pass"])) {
                error_log("\n" . "Someone tried to send a form without submitting it first or didn't set the email of the user to be edited");
                throw new Exception("Invalid request");
            }

            if (empty($_POST["pass"])) {
                error_log("\n" . "Not all fields were set, invalid form", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("You can't send a form without filling all the fields");
            }

            if ($dbManager->editUserPass($_POST["userEmail"])) 
                $_SESSION["success"] = "Your changes were applied successfully!";
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
        }
    }
    
    header("Location: ../manageUsers.php"); // Covers both invalid request and invalid login 
    exit;    
?>