<?php
    session_start();
    
    require("./scripts/errInitialize.php");
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

    <main class=main_container>
        <fieldset class="register_section">
            <h2>Sign up here:</h2>
            
            <div class="inputBox">
                <label for="firstName">First name: </label>
                <input required type ="text" id="firstName" name="firstName" placeholder="First name">       
            </div>

            <div class="inputBox">
                <label for="lastName">Last Name: </label>
                <input required type ="text" id="lastName" name="lastName" placeholder="Last name">
            </div>
                            
            <div class="inputBox">
                <label for="userName">Username: </label>
                <input required type ="text" id="userName" name="userName" placeholder="Username">
            </div>
                            
            <div class="inputBox">
                <label for="email">E-Mail: </label>
                <input required type="email" id="email" name="email" placeholder="Email"> 
            </div>

            <div class="inputBox">
                <label for="password">Password: </label>
                <input required type="password" id="password" name="password" placeholder="Password">
            </div>

            <div class="inputBox">
                <label for="confirmPwd">Confirm Password: </label>
                <input required type="password" id="confirmPwd" name="confirmPwd" placeholder="Confirm Password">
            </div>
                            
            <input type="submit" class="formButton" value="Submit">
            <br>
            <a class="formButton" href="/SAW/SAWFinalProject/php/login.php">Already a user?</a>
                
        </fieldset>
    </main>

    <?php include("./shared/footer.html"); ?>
</body>
</html>