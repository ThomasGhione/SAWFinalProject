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
            

            <?php
                if(isset($_SESSION["error"])) {
                    echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                    unset($_SESSION["error"]);
                }
                else
                    echo "<p class='error'>&nbsp;</p>";
                
                
                $firstName = "";
                $lastName = "";
                $username = "";
                $email = "";
                $password = "";    
            ?>

            <form method="post" action="./scripts/registrationForm.php">
            
                <div class="inputBox">
                    <label for="firstName">First name: </label>
                    <input required type ="text" id="firstName" name="firstName" placeholder="First name" value="<?php echo $firstName ?>">       
                </div>
                
                <div class="inputBox">
                    <label for="lastName">Last Name: </label>
                    <input required type ="text" id="lastName" name="lastName" placeholder="Last name" value="<?php echo $lastName ?>">
                </div>
                                
                <div class="inputBox">
                    <label for="userName">Username: </label>
                    <input required type ="text" id="userName" name="userName" placeholder="Username" value="<?php echo $username ?>">
                </div>
                                
                <div class="inputBox">
                    <label for="email">E-Mail: </label>
                    <input required type="email" id="email" name="email" placeholder="Email" value="<?php echo $email ?>"> 
                </div>
                
                <div class="inputBox">
                    <label for="password">Password: </label>
                    <input required type="password" id="password" name="password" placeholder="Password" value="<?php echo $password ?>">
                </div>
                
                <div class="inputBox">
                    <label for="confirmPwd">Confirm Password: </label>
                    <input required type="password" id="confirmPwd" name="confirmPwd" placeholder="Confirm Password" value="<?php echo $firstName ?>">
                </div>
                                
                <input type="submit" class="formButton" value="Submit">
                <br>
                <a class="formButton" href="/SAW/SAWFinalProject/php/login.php">Already a user?</a>
            
            </form>
        </fieldset>
    </main>

    <?php include("./shared/footer.html"); ?>
</body>
</html>