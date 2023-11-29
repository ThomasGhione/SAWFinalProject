<?php
    session_start();

    require_once('./phpClasses/dbManager.php');
    require_once('./phpClasses/sessionManager.php')

    // TODO Gestione pagina, deve ammettere solo admin


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('./shared/commonHead.php'); ?>

    <meta charset="UTF-8">
    
    <title>OpenHub - All Users Page</title>
</head>
<body>
    <?php require_once('./shared/nav.php'); ?>
    
    <main class='main_container'>
        

    </main>

    <?php require_once('./shared/footer.html')?>
</body>
</html>