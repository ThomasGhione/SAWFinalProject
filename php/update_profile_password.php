<?php 
    require("./shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ./loginForm.php");
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>
    <title>OpenHub - Change your Password</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    
    <main class="mainContainer">

        <fieldset class="formSection">
            <h2>Change your password:</h2>
                        
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

            <form action="./scripts/update_password.php" method="post">
                <div class="inputBox">
                    <label for="oldPassword">Old Password: </label>
                    <input required type="text" id="oldPassword" name="oldPassword" placeholder="Firstname">
                </div>
                
                <div class="inputBox">
                    <label for="newPassword">New Password: </label>
                    <input required type="text" id="newPassword" name="newPassword" placeholder="Lastname">
                </div>

                <input type="submit" class="formButton" name="submit" value="Change your password">
            </form>
        </fieldset>

    </main>


    <?php include("./shared/footer.php"); ?>
</body>
</html>