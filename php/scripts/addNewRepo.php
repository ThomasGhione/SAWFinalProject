<?php
    require("../shared/initializePage.php");  
    
    if (!$sessionManager->isSessionSet()) {
        header("Location: ../../index.php");
        exit;
    }


    if ($_SERVER["REQUEST_METHOD"] != "POST") 
        $_SESSION["error"] = "Invalid request";
    else {
        $errorFlag = 0;

        $fileType = $_FILES["fileUpload"]["type"];
        if ($fileType != "application/zip") {
            $_SESSION["error"] = "Uploaded file is not a .zip file";
            $errorFlag = 1;
        }

        $fileName = $_POST["reposName"];
        $email = $sessionManager->getEmail();
        if (is_dir("/SAW/SAWFinalProject/repos/$email/$fileName")) {
            $_SESSION["error"] = "A repo with this name already exists, please chooese another name";
            $errorFlag = 1;
        }

        if (!$errorFlag && $dbManager->addNewRepo($email)) {
            header("Location: ../show_profile.php");
            exit;
        }
    
    }

    header("Location: ../addNewRepoForm.php");
    exit;

?>