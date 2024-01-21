<?php 
    require("./shared/initializePage.php");

    $doSearch = !empty($_POST["searchBar"]);

    require("./shared/banCheck.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("./shared/commonHead.php"); ?>

    <link rel="stylesheet" type="text/css" href="../CSS/tableStyle.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"> </script>
    
    <title>OpenHub - Search Area</title>
</head>

<body>
    <?php require("./shared/nav.php"); ?>

    <main class="mainContainer">

        <div class="column">
            <?php if ($doSearch) $dbManager->searchUsers($_POST["searchBar"]); ?>
        </div>

        <div class="column">
            <?php if ($doSearch) $dbManager->searchRepos($_POST["searchBar"]); ?>
        </div>

        <?php unset($dbManager) ?>

    </main>

    <script>

        $(document).ready( function () {
            $('#table-searchUsers').DataTable();
            $('#table-searchRepos').DataTable();
        } );

    </script>


    <?php include("./shared/footer.php") ?>
</body>
</html>