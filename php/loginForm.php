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
    <title>OpenHub - Login Page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    
    <main class="mainContainer">

        <fieldset class="formSection">
            <legend>Log in here:</legend>
                        
            <?php
                if (isset($_SESSION["error"])) {
                    echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                    unset($_SESSION["error"]);
                }
                elseif (isset($_SESSION['success'])) {
                    echo "<p class='success'>" . $_SESSION["success"] . "</p>";
                    unset($_SESSION["success"]);
                }
            ?>

            <form id="form" action="./scripts/login.php" method="post">
                <div class="inputBox"> 
                    <label for="email">E-Mail:</label>
                    <input required type="email" id="email" name="email" placeholder="E-Mail (required)">
                </div>
                
                <div class="inputBox">
                    <label for="pass">Password:</label>
                    <input required type="password"  id="pass" name="pass" placeholder="Password (required)">
                </div>

                <div class="inputBox">
                    <label for="rememberMe">Remember Me:</label>
                    <input type="checkbox" id="rememberMe" name="rememberMe">
                </div>

                <input type="submit" class="formButton" name="submit" value="Login">
            </form>
            
            <button id="resetData" class="formButton">Cancel all data</button>
        </fieldset>

    </main>


    <?php include("./shared/footer.php"); ?>

    <script>
        emailFlag = false;
        emailMessage = "";

        document.getElementById("resetData").addEventListener("click", function (event) {
            let el1 = document.getElementById("email");
            let el2 = document.getElementById("pass");

            el1.value = "";
            el2.value = "";
        });

        document.getElementById("form").addEventListener("submit", function (event) {
            let email = document.getElementById("email").value.trim();
            let pass = document.getElementById("pass").value.trim();

            let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (email === "" || pass === "") {
                event.preventDefault();
                alert("You can't set an empty field");
            }

            if (!emailRegex.test(email)) {
                event.preventDefault();
                alert("Invalid email");
            }
        });    
    </script>
</body>
</html>