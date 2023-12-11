<?php
    require('./phpClasses/sessionManager.php');
    require("./scripts/errInitialize.php");

    $sessionManager = new sessionManager();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>
    <title>OpenHub - Login Page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    
    <main class="main_container">

        <fieldset class="register_section">
            <h2>Log in here:</h2>
                        
            <?php
                if ( isset($_SESSION["error"]) ) {
                    echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                    unset($_SESSION["error"]);
                }
                else if ( isset($_SESSION['success']) ) {
                    echo "<p class='success'>" . $_SESSION['success'] . "</p>";
                    unset($_SESSION['success']);
                }
                else
                    echo "<p class='error'>&nbsp;</p>";

                $email = "";
                $password = "";   
            ?>

            <form method='post' action="./scripts/loginForm.php">
                <div class="inputBox">
                    <label for="email">E-Mail:</label>
                    <input required type="email" id="email" name="email" placeholder="Email"> 
                </div>
    
                <div class="inputBox">
                    <label for="password">Password:</label>
                    <input required type="password" id="password" name="password" placeholder="Password">
                </div>
    
                <div class="inputBox">
                    <label for="rememberme">Remember Me:</label>
                    <input type="checkbox" id="rememberme" name="RememberMe" placeholder="RememberMe">
                </div>
    
                <button type="submit" class="formButton">Log in</button><br> 
            </form>

        </fieldset>

    </main>


    <?php include("./shared/footer.html"); ?>
</body>
</html>