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
    <link rel="stylesheet" type="text/css" href="../../CSS/newsletterPage.css">

    <link rel="stylesheet" type="text/css" href="../../CSS/tableStyle.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"> </script>

    <title>OpenHub - Admin Tools Page</title>
</head>
<body>
    <?php require_once("../shared/nav.php") ?> 
    
    <main class="mainContainer">
        <form action="./adminScripts/sendEmail.php" method="post">
            <div class="newsletter-container">
                <?php 
                    $rows = $dbManager->manageSubbedToNewsletter();

                    echo "
                    <table id='table-manageNewsletter'>
                    <thead>
                        <tr><th>Firstname</th><th>Lastname</th><th>Email</th><th>Send Email</th></tr>
                    </thead>
                    <tbody>
                    ";
    
                    foreach ($rows as $row) {
                        echo "<tr>";
    
                        echo "<td>" . $row["firstname"] . "</td>";
                        echo "<td>" . $row["lastname"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td><input type='checkbox' name='userCheckbox[]' value='" . $row["email"] . "'></td>";
        
                        echo "</tr>";
                    }
    
                    echo "
                        </tbody>
                        </table>
                    ";

                ?>
                <textarea name="message" id="textArea" rows="6" cols="50" style="resize: none;"></textarea>
                <input type="hidden" name="selectedUsers" id="selectedUsersInput">
                <input type="submit" class="newsletter-form-button" value="Submit">
            </div>
        </form>
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
        $(document).ready( function () {
            $('#table-manageNewsletter').DataTable();
        } );

        // Array to store selected users
        var selectedUsers = [];
        var emails = "";

        // Add click event handler to checkboxes
        document.querySelectorAll('input[type=checkbox]').forEach(function(checkbox) {
            checkbox.addEventListener('click', function() {
                var email = this.value;

                if (this.checked) {
                    // Add user to array
                    selectedUsers.push(email);
                } else {
                    // Remove user from array
                    var index = selectedUsers.indexOf(email);
                    if (index !== -1) selectedUsers.splice(index, 1);
                }

                // Convert array to comma-separated string
                emails = selectedUsers.join(',');

                // Add emails to form as a hidden input
                var input = document.getElementById('selectedUsersInput');
                if (!input) {
                    input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selectedUsers';
                    input.id = 'selectedUsersInput';
                    document.querySelector('form').appendChild(input);
                }
                input.value = emails;
            });
        });
    </script>


</body>
</html>