<?php 
    require("../shared/initializePage.php");    

    require_once("../phpClasses/user.php");

    if ($sessionManager->isSessionSet()) {
        header("Location: ../show_profile.php");
        exit;
    }

    try {
        if ($_SERVER["REQUEST_METHOD"] != "POST") { // invalid request
            error_log("Invalid request", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Something went wrong, please try again later");
        }         
        
        $user = new User(false);        

        header("Location: " .($dbManager->registerUser($user)
            ? "../loginForm.php"         // valid registration
            : "../registrationForm.php") // invalid registration
        );
        exit;
    }
    catch (Exception $e) { // invalid request
        $_SESSION["error"] = $e->getMessage();
        header("Location: ../registrationForm.php");
        exit;
    }

?>