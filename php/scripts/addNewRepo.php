<?php
    require("../shared/initializePage.php");  
    
    if (!$sessionManager->isSessionSet()) {
        header("Location: ../loginForm.php");
        exit;
    }


    if ($_SERVER["REQUEST_METHOD"] != "POST") 
        $_SESSION["error"] = "Invalid request";
    else {
        try {
            if (!isset($_POST["submit"]) || !isset($_POST["reposName"]) || empty($_POST["reposName"]) || !isset($_FILES["fileUpload"]["type"]) || empty($_FILES["fileUpload"]["type"])){
                error_log("[" . date("Y-m-d H:i:s") . "] One or more fields are empty in addNewRepo.php". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("One or more fields are empty");
            }
            
            $fileType = $_FILES["fileUpload"]["type"];
            // Check if file is a .zip file, "application/zip" is the MIME type for .zip files in linux, "application/x-zip-compressed" is the MIME type for .zip files in windows
            if (($fileType != "application/zip") && ($fileType != "application/x-zip-compressed")) {
                error_log("[" . date("Y-m-d H:i:s") . "] Uploaded file is not a .zip file, contenuto di filetype: $fileType, contenuto di FILES: " . $_FILES["fileUpload"]["type"]. "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("Uploaded file is not a .zip file");
            }
    
            $repoName = trim($_POST["reposName"]);
            $email = $sessionManager->getEmail();
            if (is_dir("/chroot/home/S5311626/public_html/repos/$email/$repoName")) {
                error_log("[" . date("Y-m-d H:i:s") . "] A repo with this name already exists, please chooese another name". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("A repo with this name already exists, please chooese another name");
            }

            if (preg_match("/[.,\/]/", $repoName)) {
                error_log("[" . date("Y-m-d H:i:s") . "] $email tried to create a repo with a name that contained invalid characters". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("You cannot create a repo that contains these invalid characters , . /");
            }

            $fileNameWithoutExtension = pathinfo($_FILES["fileUpload"]["name"], PATHINFO_FILENAME);
            if (preg_match("/[.,\/]/", $fileNameWithoutExtension)) {
                error_log("[" . date("Y-m-d H:i:s") . "] $email tried to upload a file with a name that contained invalid characters". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("You cannot upload a file that contains these invalid characters , . /");
            }
    
            if ($dbManager->addNewRepo($email)) 
                $_SESSION["success"] = "Repo created successfully";
        }
        catch (Exception $e) {
            $_SESSION["error"] = $e->getMessage();
        }
    }

    header("Location: ../addNewRepoForm.php");
    exit;

?>