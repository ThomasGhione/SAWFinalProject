<?php
    session_start();
    
    require("./scripts/errInitialize.php");
    require_once('./phpClasses/sessionManager.php');
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