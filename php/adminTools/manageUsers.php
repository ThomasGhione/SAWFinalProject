<?php 
    require("../shared/initializePage.php");

    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../index.php");
        exit;
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("../shared/commonHead.php"); ?>
    <link rel="stylesheet" type="text/css" href="../../CSS/tableStyle.css">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"> </script>
    
    <title>OpenHub - All Users Page</title>
</head>
<body>
    <?php require_once("../shared/nav.php") ?>
    
    <main class="mainContainer">
        <?php $dbManagerAdmin->manageUsers() ?>
    </main>

    <?php
        
        if (isset($_SESSION["error"])) {
            echo "<p class='error'>" . $_SESSION["error"] . "</p>";
            unset($_SESSION["error"]);
        }
        elseif (isset($_SESSION['success'])) {
            echo "<p class='success'>" . $_SESSION["success"] . "</p>";
            unset($_SESSION["success"]);
        }

    ?>
    <?php require_once("../shared/footer.php") ?>

    <script>

        function confirmDelete() {
            return confirm("Are you sure to delete this user?");
        }

        $(document).ready( function () {
            $('#table-manageUsers').DataTable();
        } );

    </script>

</body>
</html>
