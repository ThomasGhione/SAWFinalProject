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
    <link rel="stylesheet" type="text/css" href="../../CSS/tableStyle.css">
    <title>OpenHub - Admin Tools Page</title>
</head>
<body>
    <?php require_once("../shared/nav.php") ?> 
    
    <main class="mainContainer">
        <section class="column">
            <?php $dbManager->manageSubbedToNewsletter() ?>
        </section>
    </main>

    <?php require_once("../shared/footer.php") ?>
</body>
</html>