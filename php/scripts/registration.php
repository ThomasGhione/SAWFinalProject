<?php 
    require($_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/php/shared/initializePage.php");    

    require_once($_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/php/phpClasses/user.php");

    if ($sessionManager->isSessionSet()) {
        header("Location: /SAW/SAWFinalProject/php/show_profile.php");
        exit;
    }

    if ($_SERVER["REQUEST_METHOD"] != "POST")
        $_SESSION["error"] = "Invalid request";
            
    $user = new User(false);        
    
    $dbManager->registerUser($user); // If it has problems, it returns from registerUser method

    header("Location: /SAW/SAWFinalProject/php/loginForm.php");   // Valid registration
    exit;
?>