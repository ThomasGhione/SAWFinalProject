<?php 
    require("./shared/initializePage.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("./shared/commonHead.php"); ?>

    <link rel="stylesheet" type="text/css" href="../CSS/tableStyle.css">
    <title>OpenHub - Search Area</title>
</head>

<body>
    <?php require("./shared/nav.php"); ?>

    <main class="mainContainer">

        <?php
            $dbManager->searchUsers($_POST["searchBar"]);
        ?>

    </main>


    <?php include("./shared/footer.php") ?>
</body>
</html>