<?php
    require_once("../scripts/errInitialize.php");
    require_once('../phpClasses/sessionManager.php');

    $sessionManager = new sessionManager();

    // TODO Code to check if cookie is set
    // if (!$sessionManager->isSessionSet() && !$sessionManager->isAdmin() ) {
        // header('Location: ../index.php');
        // exit;
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('../shared/commonHead.php'); ?>
    <title>OpenHub - Admin Tools Page</title>
</head>
<body>
    <?php require_once('../shared/nav.php'); ?>

     // TODO Complete page for admin 
    <div class="admin-menu">
        <h2>Admin Menu</h2>
        
        <button onclick="window.location.href = 'allAdmins.php';">All Admins</button>
        <button onclick="window.location.href = 'manageAdmins.php';">Manage Admins</button>
        
        <button onclick="window.location.href = 'allUsers.php';">All Users</button>
        <button onclick="window.location.href = 'manageUsers.php';">Manage Users</button> <!-- ban, change infos, etc...-->
        
        <button onclick="window.location.href = 'allBannedUsers.php';">All Banned Users</button>
        
        <button onclick="window.location.href = 'allRepos.php';">All Repos</button>
        <button onclick="window.location.href = 'allSponsors.php';">All Sponsors</button>
        <button onclick="window.location.href = 'allBadges.php';">All Badges</button>

        <button onclick="window.location.href = 'manageNewsletter.php';">Manage Newsletter</button>
    </div>

    
    
    
    <?php require_once('../shared/footer.php')?>
</body>
</html>