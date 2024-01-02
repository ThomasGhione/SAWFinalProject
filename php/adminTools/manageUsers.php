<?php 
    require("../shared/initializePage.php");

    // if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
    //     header("Location: ../../index.php");
    //     exit;
    // }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("../shared/commonHead.php"); ?>
    <link rel="stylesheet" type="text/css" href="../../CSS/tableStyle.css">
    <title>OpenHub - All Users Page</title>
</head>
<body>
    <?php require_once("../shared/nav.php") ?>
    
    <main class="mainContainer">
        <?php $dbManager->manageUsers() ?>
    </main>

    <?php require_once("../shared/footer.php") ?>
</body>
</html>