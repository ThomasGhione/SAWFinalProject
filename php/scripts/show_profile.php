<?php
    require("../shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../loginForm.php");
        exit;
    }

    require("../shared/banCheck.php");

    require_once("../phpClasses/loggedUser.php");

    $currentUser = new loggedUser($sessionManager->getEmail()); // sets user data obtained from database
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("../shared/commonHead.php") ?>

    <link rel="stylesheet" type="text/css" href="../../CSS/personalArea.css">
    <link rel="stylesheet" type="text/css" href="../../CSS/tableStyle.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"> </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"> </script>
    <script src="https://kit.fontawesome.com/e856a5c7fb.js" crossorigin="anonymous"></script>

    <title>OpenHub - Personal Area</title>
</head>
<body>
    <?php include("../shared/nav.php") ?>

    <div class="main_personalarea">
        <div class="left_column">

            <div class="infos">
                <?php 
                    if (!($pfpHref = ($currentUser->getPfp())))
                        $pfpHref = "default.jpg";
                
                    echo "<img class='pfp' src='../../images/pfps/$pfpHref' alt='Your profile picture'>";

                    echo "<p>Welcome " . $currentUser->getFirstname() . " " . $currentUser->getLastname() . "</p>";
                    echo "<br>";
                    echo "<i class='fa-solid fa-square-envelope'>" . " " . $currentUser->getEmail() . "</i>";
                ?>
            </div>
            
            <div class="personalAreaOptions">
                <a class="personalAreaButton" href="../update_profile_form.php">Edit your profile</a>
                <a class="personalAreaButton" href="../update_profile_password_form.php">Change your password</a>
                <a class="personalAreaButton" href="../addNewRepoForm.php">Add a new repo here!</a>

                <?php
                    
                    if (!$currentUser->getNewsletter())
                        echo "<a class='personalAreaButton' href='../scripts/manageUserInNewsletter.php?sub=" . "true" . "'>Subscribe to our newsletter!</a>";
                    else
                        echo "<a class='personalAreaButton' href='../scripts/manageUserInNewsletter.php?sub=" . "false" . "'>Unsubscribe from our newsletter!</a>";

                    if (isset($_SESSION["error"])) {
                        echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                        unset($_SESSION["error"]);
                    }
                    elseif (isset($_SESSION['success'])) {
                        echo "<p class='success'>" . $_SESSION["success"] . "</p>";
                        unset($_SESSION["success"]);
                    }
                
                ?>
            </div>
        </div>
         
        <div class="right_column">

            <?php 
                $rows = $dbManager->showRepos($currentUser->getEmail());
                
                if (empty($rows))
                    echo "<p>You haven't uploaded any repo yet</p>";
                else {
                    echo "<table id='table-userRepos'>
                        <thead>
                            <tr><th>Name</th><th>Date of Creation</th><th>Last Modification<th>Update</th><th>Delete</th></tr>
                        </thead>
                        <tbody>
                        ";

                    foreach ($rows as $row) {
                        echo "<tr>";
                        
                        echo "<td>" . $row["Name"] . "</td>";
                        echo "<td>" . $row["CreationDate"] . "</td>";
                        echo "<td>" . $row["LastModified"] . "</td>";
                        echo "<td><a href='../update_repo_form.php?name=" . urlencode($row["Name"]) . "'><i class='fa-solid fa-pen'></i><span class='visually-hidden'>Update</span></a></td>";
                        echo "<td><a href='../scripts/deleteRepo.php?name=" . urlencode($row["Name"]) . "' onclick='return confirmDelete();'><i class='fa-solid fa-trash'></i><span class='visually-hidden'>Delete</span></a></td>";

                        echo "</tr>";
                    }
                    
                    echo "</tbody>
                        </table>
                    ";
                }
            ?>

        </div>
    </div>

    <?php include("./shared/footer.php") ?>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this repo?");
        }
        
        $(document).ready( function () {
            $('#table-userRepos').DataTable();
        } );

    </script>

</body>
</html>