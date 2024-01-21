<?php
    
    require ("../shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../loginForm.php");
        exit;
    }

    require("../shared/banCheck.php");

    try {
        if (!isset($_GET["name"])) {
            error_log("Bad method used or repo name not set", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Bad method used or repo name not set");
        }

        $repoName = urldecode($_GET["name"]);

        if (empty($repoName)) {
            error_log("Repo name can't be empty", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Repo name can't be empty");
        }

        if ($dbManager->deleteRepo($sessionManager->getEmail(), $repoName))
            $_SESSION["success"] = "Repo deleted successfully";
    }
    catch (Exception $e) { $_SESSION["error"] = $e->getMessage();}

    unset($dbManager);
    header("Location: ../show_profile.php");
    exit;
?>