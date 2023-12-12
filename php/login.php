<?php
    require('./phpClasses/sessionManager.php');
    require("./scripts/errInitialize.php");

    $sessionManager = new sessionManager();

    // TODO Code to check if cookie is set
    if ( $sessionManager->isSessionSet() ) {
        header('Location: ./personalArea.php');
        exit;
    }

    if ( $_SERVER["REQUEST_METHOD"] == "POST" ) {
        $_SESSION["serverStatus"] = "POST";
        $_SESSION["postData"] = $_POST["email"] . " " . $_POST["pass"];
        header("Location: ./scripts/loginForm.php");
        exit;
    }

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
            ?>

            <form action="login.php" method="post">
                <div class='inputBox'> 
                    <label for='email'>E-Mail:</label>
                    <input required type="email" name="email" placeholder="E-Mail (required)">
                </div>
                
                <div class='inputBox'>
                    <label for='pass'>Password:</label>
                    <input type="password" name="pass" placeholder="Password (required)">
                </div>

                <div class="inputBox">
                    <label for="rememberme">Remember Me:</label>
                    <input type="checkbox" id="rememberme" name="RememberMe" placeholder="RememberMe">
                </div>

                <input type="submit" class='formButton' name="submit" value="Login">
            </form>

<!--
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
-->
        </fieldset>

    </main>


    <?php include("./shared/footer.php"); ?>
</body>
</html>