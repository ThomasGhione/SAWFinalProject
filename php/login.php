<?php
    session_start();
    
    require("./phpFunc/errInitialize.php");
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
                <input required type="checkbox" id="rememberme" name="RememberMe" placeholder="RememberMe">
            </div>

            <button type="submit" class="formButton">Log in</button><br> 
            

        </fieldset>

    </main>


    <?php include("./shared/footer.html"); ?>
</body>
</html>