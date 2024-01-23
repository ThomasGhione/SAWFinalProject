<?php
    require("../php/shared/initializePage.php");    

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../php/loginForm.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] != "POST") 
        $_SESSION["error"] = "Invalid request";
    else { // Following code checks the number of arguments used in POST, it can be improved... probably :3
        try {
            if (!isset($_POST["submit"]) || !isset($_POST["firstname"]) || !isset($_POST["lastname"]) || !isset($_POST["email"])) {
                error_log("[" . date("Y-m-d H:i:s") . "] Not all fields were set, invalid form". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Something went wrong when trying to update your data, try again later");
            }

            if (empty($_POST["firstname"]) || empty($_POST["lastname"]) || empty($_POST["email"])) {
                error_log("[" . date("Y-m-d H:i:s") . "] User " . $sessionManager->getEmail() . " tried to update his data with empty fields". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("One or more required fields were empty");
            }

            if ($dbManager->editProfile($sessionManager->getEmail(), $sessionManager)) {
                $_SESSION["success"] = "Data updated successfully";
            }
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
        }
    }

    header("Location: ../php/update_profile_form.php");
    exit;
?>