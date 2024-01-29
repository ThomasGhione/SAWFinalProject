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
                error_log("[" . date("Y-m-d H:i:s") . "] One or more fields are empty in updateRepo.php". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("One or more fields are empty");
            }
            
            $fileType = $_FILES["fileUpload"]["type"];
            if (($fileType != "application/zip") && ($fileType != "application/x-zip-compressed")) {
                error_log("[" . date("Y-m-d H:i:s") . "] Uploaded file is not a .zip file, contenuto di filetype: $fileType, contenuto di FILES: " . $_FILES["fileUpload"]["type"]. "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("Uploaded file is not a .zip file");
            }

            $repoToEdit = trim($_POST["repoToEdit"]);
            
            if($dbManager->updateRepo($sessionManager->getEmail(), $repoToEdit))
                $_SESSION["success"] = "Repo updated successfully";
        }
        catch (Exception $e) { $_SESSION["error"] = $e->getMessage(); }
    }

    header("Location: ../show_profile.php");
    exit;

?>