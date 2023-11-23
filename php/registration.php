<?php
    session_start();
    
    require("./phpFunc/errInitialize.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>
    <title>OpenHub - Registration Page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    <div class="bg-image"></div>

    <main class=main_container>
        <?php include("./shared/registrationForm.php") ?>
    </main>

    <?php include("./shared/footer.html"); ?>
</body>
</html>