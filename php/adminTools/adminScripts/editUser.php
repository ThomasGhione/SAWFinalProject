<?php    
    require("../../shared/initializePage.php");    

    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../../index.php");
        exit;
    }

    $dbManagerAdmin = new dbManagerAdmin();


    if ($_SERVER["REQUEST_METHOD"] != "POST") 
        $_SESSION["error"] = "Invalid request";
    else { // Following code checks the number of arguments used in POST, it can be improved... probably :3
        try {
        
            if (!isset($_POST["submit"]) || !isset($_POST["userEmail"]) || empty($_POST["userEmail"])) {
                error_log("Someone tried to send a form without submitting it first or didn't set the email of the user to be edited");
                throw new Exception("Invalid request");
            }

            $count = 0;
            
            foreach ($_POST as $dataName => $data) 
                if (!empty($_POST[$dataName])) ++$count;

            // We used a count because it's much easier to expand the profile editing with more options
            if ($count < 3) {   // At least submit and email should be always set
                error_log("Admin must choose at least 1 field to edit", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Please choose at least 1 field to edit, number of empty values = $count");
            }
            if ($count > 7) {   // Max number of editable data is 5, so the system returns error if more data is sent 
                error_log("Someone tried to edit more data that the system admits", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Invalid request");
            }
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../manageUsers.php");
            exit;
        }

        if ($dbManagerAdmin->editUser($_POST["userEmail"])) 
            $_SESSION["success"] = "Your changes were applied successfully!";
    }
    
    header("Location: ../manageUsers.php"); // Covers both invalid request and invalid login 
    exit;    
?>