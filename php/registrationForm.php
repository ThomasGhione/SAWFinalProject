<?php
    require("./shared/initializePage.php");
    
    if ($sessionManager->isSessionSet()) {
        header("Location: ./show_profile.php");
        exit;
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>
    <title>OpenHub - Registration Page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    <div class="bg-image"></div>

    <main class="mainContainer">
        <fieldset class="formSection">
            <h2>Sign up here:</h2>
            
            <?php
                if (isset($_SESSION["error"])) {
                    echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                    unset($_SESSION["error"]);
                }
                else
                    echo "<p class='error'>&nbsp;</p>";
            ?>


            <form action="./scripts/registration.php" method="post">
                
                <div class="inputBox">
                    <label for="firstname">Firstname: </label>
                    <input required type="text" id="firstname" name="firstname" placeholder="Firstname (required)">
                </div>
                
                <div class="inputBox">
                    <label for="lastname">Lastname: </label>
                    <input required type="text" id="lastname" name="lastname" placeholder="Lastname (required)">
                </div>

                <div class="inputBox">
                    <label for="email">Email: </label>
                    <input required type="email" id="email" name="email" placeholder="Email (required)">
                </div>

                <div class="inputBox">
                    <label for="pass">Password: </label>
                    <input required type="password" id="pass" name="pass" placeholder="Password (required)">
                </div>

                <div class="inputBox">
                    <label for="confirm">Confirm Password: </label>
                    <input required type="password" id="confirm" name="confirm" placeholder="Confirm Password (required)">
                </div>

                <input type="submit" class="formButton" name="submit" value="Submit">

                <a class="formButton" href="/SAW/SAWFinalProject/php/loginForm.php">Already a user?</a>
            </form>
        </fieldset>
    </main>

    <?php include("./shared/footer.php"); ?>
</body>
</html>