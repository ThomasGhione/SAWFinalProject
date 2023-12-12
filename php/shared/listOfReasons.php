<?php 
    require_once("../scripts/errInitialize.php");
    require_once("../phpClasses/sessionManager.php");

    $sessionManager = new sessionManager();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("./commonHead.php"); ?>
    <title>OpenHub Homepage</title>
</head>

<body>
    <?php include("./nav.php") ?>

    <div class="bg-image"></div>

    <main class="main_container">

        <p>Thanks for watching</p>

    </main>


    <?php include("./footer.php") ?>
</body>
</html>