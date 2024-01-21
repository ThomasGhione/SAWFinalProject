<?php 
    require("./shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ./loginForm.php");
        exit;
    }

    require("./shared/banCheck.php");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>
    <title>OpenHub - Change your Password</title>
</head>
<body>
    <?php 
        include("./shared/nav.php"); 
        unset($dbManager);
    ?>

    
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

            <form id="form" action="./scripts/update_password.php" method="post">
                <div class="inputBox">
                    <label for="oldPassword">Old Password: </label>
                    <input required type="password" id="oldPassword" name="oldPassword" placeholder="Firstname">
                </div>
                
                <div class="inputBox">
                    <label for="newPassword">New Password: </label>
                    <input required type="password" id="newPassword" name="newPassword" placeholder="Lastname">
                </div>

                <input type="submit" class="formButton" name="submit" value="Change your password">
            </form>
        </fieldset>

    </main>


    <?php include("./shared/footer.php"); ?>

    <script>
        document.getElementById("form").addEventListener("submit", function (event) {
            let oldPassword = document.getElementById("oldPassword").value.trim();
            let newPassword = document.getElementById("newPassword").value.trim();

            if (oldPassword == "" || newPassword == ""){
                alert("You must fill all the fields");
                preventDefault();
            }
            else if (oldPassword.length < 8) {
                alert("Your old password must be at least 8 characters long");
                preventDefault();
            }
            else if (newPassword.length < 8) {
                alert("Your new password must be at least 8 characters long");
                preventDefault();
            }
            else if (oldPassword == newPassword) {
                alert("Your new password must be different from your old password");
                preventDefault();
            }
        });
    </script>
</body>
</html>