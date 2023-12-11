<?php
    require_once("./scripts/errInitialize.php");
    require_once('./phpClasses/sessionManager.php');

    $sessionManager = new sessionManager();

    // TODO Code to check if cookie is set
    if (!$sessionManager->isSessionSet() && !$sessionManager->isAdmin() ) {
        header('Location: ../index.php');
        exit;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once('./shared/commonHead.php'); ?>
    <title>OpenHub - Admin Tools Page</title>
</head>
<body>
    <?php require_once('./shared/nav.php'); ?>

    <?php // TODO Complete page for admin ?>
    
    <?php require_once('./shared/footer.html')?>
</body>
</html>