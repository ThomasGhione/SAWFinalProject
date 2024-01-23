<?php
    require("../../shared/initializePageAdmin.php");    

    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../../index.php");
        exit;
    }
    
    require_once("../../phpClasses/newsletterManager.php");
    $newsletterManager = new newsletterManager();

    try {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            error_log("[" . date("Y-m-d H:i:s") . "] Invalid request". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Invalid request");
        }

        if (!isset($_POST["selectedUsers"]) || empty($_POST["selectedUsers"])) {
            error_log("[" . date("Y-m-d H:i:s") . "] No user selected". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("No user selected");
        }

        if (!isset($_POST["message"]) || empty($_POST["message"])) {
            error_log("[" . date("Y-m-d H:i:s") . "] Message can't be empty". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
            throw new Exception("Message can't be empty");
        }

        $message = $_POST["message"];
        $usrArr = explode(",", $_POST["selectedUsers"]);

        $newsletterManager->sendNewsletter($usrArr, $message);
    }
    catch (Exception $e) { $_SESSION["error"] = $e->getMessage(); }

    header("Location: ../manageNewsletter.php");
    exit;

?>