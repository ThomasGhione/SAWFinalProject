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
</head>
<body>
    <?php require_once("../shared/nav.php"); ?>

    <div class="admin-menu">
        
        <h2>Admin Menu</h2>

        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageUsers.php">Manage Users</a>
        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageNewsletter.php">Manage Newsletter</a>

        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageRepos.php">Manage Repos</a>
        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageSponsors.php">Manage Sponsors</a>
        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageBadges.php">Manage Badges</a>

    </div>
    
    <?php require_once("../shared/footer.php")?>
</body>
</html>