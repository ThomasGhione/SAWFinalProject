<?php 
    require_once("../scripts/errInitialize.php");
    require_once("../phpClasses/dbManager.php");
    require_once("../phpClasses/cookieManager.php");
    require_once("../phpClasses/sessionManager.php");

    $sessionManager = new sessionManager();
    $sessionManager = new cookieManager();
    $dbManager = new dbManager();

    // if ( !$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
    //     $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);
    //
    // if ( !$sessionManager->isSessionSet() || !$sessionManager->isAdmin() ) {
    //     header('Location: ../../index.php');
    //     exit;
    // }

    //???
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST["banUser"]))
            $dbManager->banUser($_POST["ban"]);
        elseif (isset($_POST["editUser"]))
            $dbManager->editUser($_POST["unban"]);
        elseif (isset($_POST["deleteUser"]))
            $dbManager->deleteUser($_POST["delete"]);
    }
    
    //???
    $dbManager->allUsers();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("../shared/commonHead.php"); ?>
    <link rel="stylesheet" type="text/css" href="allUsersStyle.css">
    <title>OpenHub - All Users Page</title>
</head>
<body>
    <?php require_once("../shared/nav.php"); ?>
    
    <main class="main_container">
        
        

    </main>

    <?php require_once("../shared/footer.php")?>
</body>
</html>