<?php
    require("../shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../../index.php");
        exit;
    }

    require_once("../phpClasses/newsletterManager.php");
    $newsletterManager = new newsletterManager();

    // TODO FINIRE ERROR CHECKING

    // Following code checks if in link there's a sub value, and checks if sub is "true" or "false" (they're strings)
    if (isset($_GET["sub"]) && ($_GET["sub"] == "true" || $_GET["sub"] == "false")) {
        
        $sub = ($_GET["sub"] === "true");
        
        if ($newsletterManager->setNewsletter($dbManager, $sessionManager->getEmail(), $sub)) {
            if ($sub) 
                $_SESSION["success"] = "You are now subscribed to the newsletter";
            else 
                $_SESSION["success"] = "You are now unsubscribed from the newsletter";
        }
    }
    else {
        // If user tries to manipulate the link in a bad way, it triggers this error
        $_SESSION["error"] = "Something went wrong, try again later";
    }

    // It manages both success and error situations
    header("Location: ../show_profile.php");
    exit;
?>