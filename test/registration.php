<?php 
    require("../php/shared/initializePage.php");    

    require_once("../php/phpClasses/user.php");

    if ($sessionManager->isSessionSet()) {
        header("Location: ./show_profile.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] != "POST")
        $_SESSION["error"] = "Invalid request";
            
    $user = new User(false);        
    
    $dbManager->registerUser($user); // If it has problems, it returns from registerUser method

    header("Location: ../php/loginForm.php");   // Valid registration
    exit;
?>