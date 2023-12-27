<?php
    require_once("./errInitialize.php");
    require_once("../phpClasses/dbManager.php");
    require_once("../phpClasses/cookieManager.php");
    require_once("../phpClasses/sessionManager.php");
    
    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();
    
    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);
    
    if (!$sessionManager->isSessionSet()) {
        header("Location: ../..index.php");
        exit;
    }


    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
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
    else 
        $_SESSION["error"] = "Invalid request";

    header("Location: ../addNewRepoForm.php");
    exit;


?>