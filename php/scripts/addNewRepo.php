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
            if (($fileType != "application/zip") && ($fileType != "application/x-zip-compressed")) {
                error_log("Uploaded file is not a .zip file, contenuto di filetype: $fileType, contennuto di FILES: " . $_FILES["fileUpload"]["type"], 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Uploaded file is not a .zip file");
            }
    
            $repoName = trim($_POST["reposName"]);
            $email = $sessionManager->getEmail();
            if (is_dir("/SAW/SAWFinalProject/repos/$email/$repoName")) {
                error_log("A repo with this name already exists, please chooese another name", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("A repo with this name already exists, please chooese another name");
            }

            $fileName = $_FILES["fileUpload"]["name"];
            if (preg_match("/[.,\/]/", $repoName)) {
                error_log("$email tried to create a repo with a name that contained invalid characters", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("You cannot create a repo that contains these invalid characters , . /");
            }

            if (preg_match("/[.,\/]/", $fileName)) {
                error_log("$email tried to upload a file with a name that contained invalid characters", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("You cannot upload a file that contains these invalid characters , . /");
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