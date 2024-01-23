<?php
    require("../shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../loginForm.php");
        exit;
    }

    require_once("../phpClasses/newsletterManager.php");
    $newsletterManager = new newsletterManager();

    try {
        // Following code checks if there's a sub value within a link
        // also checks if sub is "true" or "false" treating it as a string.
        
        if (!isset($_GET["sub"]) && ($_GET["sub"] == "true" || $_GET["sub"] == "false")) {
            error_log("[" . date("Y-m-d H:i:s") . "] Invalid request". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Invalid request");
        }
            
        $sub = ($_GET["sub"] === "true");
        
        if ($newsletterManager->setNewsletter($dbManager, $sessionManager, $sessionManager->getEmail(), $sub))
            $_SESSION["success"] =
                "You are now" .
                    ($sub
                        ? "subscribed to"
                        : "unsubscribed from")
                . " the newsletter";
        
    } // falls here if user tries to access the page in a bad way (e.g. by manipulating the link)
    catch (Exception $e) { $_SESSION["error"] = $e->getMessage(); }

    header("Location: ../show_profile.php");
    exit;
?>