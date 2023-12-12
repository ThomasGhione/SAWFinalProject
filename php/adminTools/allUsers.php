<?php 
    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');

    $sessionManager = new sessionManager();

    // TODO Code to check if cookie is set
    // if (!$sessionManager->isSessionSet() && !$sessionManager->isAdmin() ) {
    //     header('Location: ../../index.php');
    //     exit;
    // }

    $dbManager = new dbManager();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('../shared/commonHead.php'); ?>
    <link rel='stylesheet' type='text/css' href='allUsersStyle.css'>
    <title>OpenHub - All Users Page</title>
</head>
<body>
    <?php require_once('../shared/nav.php'); ?>
    
    <main class='main_container'>
        
        <?php $dbManager->allUsers(); ?>

    </main>

    <?php require_once('../shared/footer.php')?>
</body>
</html>