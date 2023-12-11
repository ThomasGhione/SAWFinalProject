<?php
    $sessionManager = new sessionManager(); 
    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');

    // TODO Gestione pagina, deve ammettere solo admin

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

    <?php require_once('../shared/footer.html')?>
</body>
</html>