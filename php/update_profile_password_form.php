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
    ?>

    
    <main class="mainContainer">

        <fieldset class="formSection">
            <legend>Change your password:</legend>
                        
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
                    <input required type="password" id="oldPassword" name="oldPassword" placeholder="Old password">
                </div>
                
                <div class="inputBox">
                    <label for="newPassword">New Password: </label>
                    <input required type="password" id="newPassword" name="newPassword" placeholder="New password">
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
                event.preventDefault();
                alert("You must fill all the fields");
            }
            else if (oldPassword.length < 8) {
                event.preventDefault();
                alert("Your old password must be at least 8 characters long");
            }
            else if (newPassword.length < 8) {
                event.preventDefault();
                alert("Your new password must be at least 8 characters long");
            }
            else if (oldPassword == newPassword) {
                event.preventDefault();
                alert("Your new password must be different from your old password");
            }
        });
    </script>
</body>
</html>