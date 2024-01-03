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

    <title>OpenHub - Admin Tools Page</title>
</head>
<body>
    <?php require_once("../shared/nav.php") ?> 
    
    <main class="mainContainer">
        <form action="../scripts/sendEmail.php" method="post">
    
        <?php $dbManagerAdmin->manageSubbedToNewsletter() ?>
        <textarea name="message" rows="6" cols="50" style="resize: none;"></textarea>
        <input type="submit" value="Submit">

        </form>
    </main>

    <?php require_once("../shared/footer.php") ?>

    <script>
        $(document).ready( function () {
            $('#table-manageNewsletter').DataTable();
        } );
    </script>

</body>
</html>