<?php 
    require("../../shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ./loginForm.php");
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("../../shared/commonHead.php") ?>
    <title>OpenHub - Update your profile page</title>
</head>
<body>
    <?php include("../../shared/nav.php"); ?>

    
    <main class="mainContainer">

        <fieldset class="formSection">
            <h2>Edit your profile here:</h2>
                        
            <?php
                if (isset($_SESSION["error"])) {
                    echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                    unset($_SESSION["error"]);
                }
                elseif (isset($_SESSION["success"])) {
                    echo "<p class='success'>" . $_SESSION["success"] . "</p>";
                    unset($_SESSION["success"]);
                }
                else
                    echo "<p class='error'>&nbsp;</p>"; 
            ?>

            <form action="editUser.php" method="post">
                <div class="inputBox">
                    <label for="firstname">Firstname: </label>
                    <input type="text" id="firstname" name="firstname" placeholder="Firstname">
                </div>
                
                <div class="inputBox">
                    <label for="lastname">Lastname: </label>
                    <input type="text" id="lastname" name="lastname" placeholder="Lastname">
                </div>

                <div class="inputBox">
                    <label for="email">Email: </label>
                    <input type="email" id="email" name="email" placeholder="Email">
                </div>

                <!-- TODO we should be able to edit all user's data -->

                <input type="submit" class="formButton" name="submit" value="Edit">
            </form>
        </fieldset>

    </main>


    <?php include("../../shared/footer.php"); ?>
</body>
</html>