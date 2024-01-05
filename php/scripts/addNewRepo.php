<?php
    require("../shared/initializePage.php");  
    
    if (!$sessionManager->isSessionSet()) {
        header("Location: ../../index.php");
        exit;
    }


    if ($_SERVER["REQUEST_METHOD"] != "POST") 
        $_SESSION["error"] = "Invalid request";
    else {
        try {
            if (!isset($_POST["submit"]) || !isset($_POST["reposName"]) || empty($_POST["reposName"]) || !isset($_FILES["fileUpload"]["type"]) || empty($_FILES["fileUpload"]["type"])){
                error_log("One or more fields are empty in addNewRepo.php", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("One or more fields are empty");
            }
            
            $fileType = $_FILES["fileUpload"]["type"];
            if ($fileType != "application/zip") {
                error_log("Uploaded file is not a .zip file", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Uploaded file is not a .zip file");
            }
    
            $fileName = trim($_POST["reposName"]);
            $email = $sessionManager->getEmail();
            if (is_dir("/SAW/SAWFinalProject/repos/$email/$fileName")) {
                error_log("A repo with this name already exists, please chooese another name", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("A repo with this name already exists, please chooese another name");
            }
    
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
            header("Location: ../addNewRepoForm.php");
            exit;
        }

        if ($dbManager->addNewRepo($email)) 
            $_SESSION["success"] = "Repo created successfully";
    }

    header("Location: ../addNewRepoForm.php");
    exit;

?>