<?php 
    require("./shared/initializePage.php");

    $doSearch = !empty($_POST["searchBar"]);
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

        <section class="column">
            <?php if ($doSearch) $dbManager->searchUsers($_POST["searchBar"]); ?>
        </section>

        <section class="column">
            <?php if ($doSearch) $dbManager->searchRepos($_POST["searchBar"]); ?>
        </section>

    </main>


    <?php include("./shared/footer.php") ?>
</body>
</html>