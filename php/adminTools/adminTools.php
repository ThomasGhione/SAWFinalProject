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

     // TODO Complete page for admin 
    <div class="admin-menu">
        <h2>Admin Menu</h2>

        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/allAdmins.php">All Admins</a>
        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageAdmins.php">Manage Admins</a>

        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/allUsers.php">All Users</a>
        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageUsers.php">manageUsers</a>
        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/allBannedUsers.php">All Banned Users</a>

        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/allRepos.php">All repos</a>
        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/allSponsors.php">All Sponsors</a>
        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/allBadges.php">All Badges</a>

        <a class="navButton" href="/SAW/SAWFinalProject/php/adminTools/manageNewsletter.php">Manage Newsletter</a>
    </div>
    
    <?php require_once("../shared/footer.php")?>
</body>
</html>