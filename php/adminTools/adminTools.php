<?php
    require("../shared/initializePageAdmin.php");
    
    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../index.php");
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("../shared/commonHead.php"); ?>
    <title>OpenHub - Admin Tools Page</title>
    <link rel="stylesheet" type="text/css" href="../../CSS/adminMenu.css">
</head>
<body>
    <?php require_once("../shared/nav.php"); ?>
    
    <main class="mainContainer">
        
        <div class="admin-menu">
            <h2>Admin Menu</h2>

            <a class="admin-menu-button" href="/SAW/SAWFinalProject/php/adminTools/manageUsers.php">Manage Users</a>
            <a class="admin-menu-button" href="/SAW/SAWFinalProject/php/adminTools/manageNewsletter.php">Manage Newsletter</a>
        </div>
    </main>
    
    <?php require_once("../shared/footer.php")?>
</body>
</html>