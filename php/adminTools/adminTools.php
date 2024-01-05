<?php
    require("../shared/initializePage.php");
    
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
    <link rel="stylesheet" href="../../css/adminMenu.css">
</head>
<body>
    <?php require_once("../shared/nav.php"); ?>

    <div class="admin-menu">
        
        <h2 id="text">Admin Menu</h2>

        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageUsers.php">Manage Users</a>
        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageNewsletter.php">Manage Newsletter</a>
    </div>
    
    <?php require_once("../shared/footer.php")?>
</body>
</html>