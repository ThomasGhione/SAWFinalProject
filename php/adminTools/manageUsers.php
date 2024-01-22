<?php 
    require("../shared/initializePageAdmin.php");

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
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"> </script>

    <script src="https://kit.fontawesome.com/e856a5c7fb.js" crossorigin="anonymous"></script>

    <title>OpenHub - All Users Page</title>
</head>
<body>
    <?php require_once("../shared/nav.php") ?>
    
    <main class="mainContainer">
        <?php 
            $rows = $dbManager->manageUsers();

            echo "
            <table id='table-manageUsers'>
            <thead>
                <tr><th>Firstname</th><th>Lastname</th><th>Email</th><th>Role</th><th>Delete</th><th>Edit</th><th>Ban</th><th>Unban</th></tr>
            </thead>
            <tbody>
            ";

            foreach ($rows as $row) {
                
                $isBanned = ($row["permission"] === "banned");
                
                echo "<tr>";
                
                echo "<td>" . $row["firstname"] . "</td>";
                echo "<td>" . $row["lastname"] . "</td>";
                echo "<td>" . $row["email"] . "</td>";
                echo "<td>" . $row["permission"] . "</td>";
                echo "<td><a href='./adminScripts/deleteUser.php?email=" . urlencode($row["email"]) . "' onclick='return confirmDelete();'><i class='fa-solid fa-trash'></i></a></td>";
                echo "<td><a href='./editUserForm.php?email=" . urlencode($row["email"]) . "'><i class='fa-solid fa-pencil'></i></a></td>";
                
                echo "<td>";
                if ($isBanned)
                    echo "<span class='emptyButton'><i class='fa-solid fa-ban'></i></span>";
                else   
                    echo "<a href='./adminScripts/banUser.php?email=" . urlencode($row["email"]) .  "' onclick='return confirmBan();'><i class='fa-solid fa-ban'></i></a>";
                echo "</td>";
                
                echo "<td>";
                if ($isBanned)
                    echo "<a href='./adminScripts/unbanUser.php?email=" . urlencode($row["email"]) .  "' onclick='return confirmUnBan();'><i class='fa-solid fa-check'></i></a>";
                else
                    echo "<span class='emptyButton'><i class='fa-solid fa-check'></i></span>";
                echo "</td>";

                echo "</tr>";
            }

            echo "</tbody>
                </table>
            ";
        ?>
    </main>

    <?php

        if (isset($_SESSION["error"])) {
            echo "<p class='error'>" . $_SESSION["error"] . "</p>";
            unset($_SESSION["error"]);
        }
        elseif (isset($_SESSION["success"])) {
            echo "<p class='success'>" . $_SESSION["success"] . "</p>";
            unset($_SESSION["success"]);
        }

    ?>
    <?php require_once("../shared/footer.php") ?>

    <script>

        function confirmDelete() {
            return confirm("Are you sure you want to delete this user?");
        }

        function confirmBan() {
            return confirm("Are you sure you want to ban this user?");
        }

        function confirmUnban() {
            return confirm("Are you sure you want to unban this user?");
        }

        $(document).ready( function () {
            $('#table-manageUsers').DataTable();
        } );

    </script>

</body>
</html>
