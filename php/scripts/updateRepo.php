<?php
    require ("../shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../loginForm.php");
        exit;
    }

    require("../shared/banCheck.php");

    if ($_SERVER["REQUEST_METHOD"] != "POST") {
        $_SESSION["error"] = "Invalid request";
    }
    else {
        try {
            if (!isset($_POST["submit"]) || !isset($_POST["repoToEdit"]) || empty($_POST["repoToEdit"]) || !isset($_FILES["fileUpload"]["type"]) || empty($_FILES["fileUpload"]["type"])){
                error_log("\n" . "One or more fields are empty in updateRepo.php", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("One or more fields are empty");
            }
            
            $fileType = $_FILES["fileUpload"]["type"];
            if (($fileType != "application/zip") && ($fileType != "application/x-zip-compressed")) {
                error_log("\n" . "Uploaded file is not a .zip file, contenuto di filetype: $fileType, contennuto di FILES: " . $_FILES["fileUpload"]["type"], 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Uploaded file is not a .zip file");
            }

            $repoToEdit = trim($_POST["repoToEdit"]);
            
            if($dbManager->updateRepo($sessionManager->getEmail(), $_POST["repoToEdit"]))
                $_SESSION["success"] = "Repo updated successfully";
        }
        catch (Exception $e) { $_SESSION["error"] = $e->getMessage(); }
    }

    header("Location: ../show_profile.php");
    exit;

?>