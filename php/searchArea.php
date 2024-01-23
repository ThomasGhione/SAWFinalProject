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
            <?php 
                if ($doSearch) {
                    $rows = $dbManager->searchUsers($_POST["searchBar"]);
                    
                    if (empty($rows)) 
                        echo "<h2>No users were found with these values</h2>";
                    else {
                        echo "
                            <table id='table-searchUsers'>
                            <caption> Users found </caption>
                            <thead>
                                <tr><th>Email</th><th>Firstname</th><th>Lastname</th></tr>
                            </thead>
                            <tbody>
                        ";
        
                        foreach ($rows as $row) {
                            echo "<tr>";
        
                            echo "<td>" . $row["email"] . "</td>";
                            echo "<td>" . $row["firstname"] . "</td>";
                            echo "<td>" . $row["lastname"] . "</td>";
        
                            echo "</tr>";
                        }
        
                        echo "</tbody>
                            </table>
                        ";   
                    }
                } 
            ?>
        </div>

        <div class="column">
            <?php 
                if ($doSearch){
                    $rows = $dbManager->searchRepos($_POST["searchBar"]);

                    if (empty($rows))
                        echo "<h2>No repos were found with these values</h2>";
                    else {
                        echo "
                            <table id='table-searchRepos'>
                            <caption> Users found </caption>
                            <thead>
                                <tr><th>Owner</th><th>Name</th><th>CreationDate</th><th>LastModified</th></tr>
                            </thead>
                            <tbody>
                        ";
    
                        foreach ($rows as $row) {
                            echo "<tr>";
        
                            echo "<td>" . $row["Owner"] . "</td>";
                            echo "<td>" . $row["Name"] . "</td>";
                            echo "<td>" . $row["CreationDate"] . "</td>";
                            echo "<td>" . $row["LastModified"] . "</td>";
                        
                            echo "</tr>";
                        }
        
                        echo "</tbody>
                            </table>
                        ";
                    }
                }
            ?>
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