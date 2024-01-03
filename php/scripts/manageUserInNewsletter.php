<?php
    require("../shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../../index.php");
        exit;
    }

    require_once("../phpClasses/newsletterManager.php");
    $newsletterManager = new newsletterManager();

    // TODO FINIRE ERROR CHECKING

    try {
        // Following code checks if there's a sub value inside a link, also checks if sub is "true" or "false" (they're strings)
        if (isset($_GET["sub"]) && ($_GET["sub"] == "true" || $_GET["sub"] == "false")) {
            
            $sub = ($_GET["sub"] === "true");
            
            if ($newsletterManager->setNewsletter($dbManager, $sessionManager, $sessionManager->getEmail(), $sub)) 
                $_SESSION = ($sub)
                    ? "You are now subscribed to the newsletter"
                    : "You are now unsubscribed from the newsletter";
        }
    }
    catch (Exception $e) { // If user tries to manipulate the link in a bad way, it triggers this error
        $_SESSION["error"] = "Something went wrong, try again later";
    } 

    header("Location: ../show_profile.php");
    exit;
?>