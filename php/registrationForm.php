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
            ?>


            <form id="form" action="./scripts/registration.php" method="post">
                
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

            </form>
            
            <div>
                <a class="formButton" href="/SAW/SAWFinalProject/php/loginForm.php">Already a user?</a>
                <button id="resetData" class="formButton">Cancel all data</button>
            </div>

        </fieldset>
    </main>

    <?php include("./shared/footer.php"); ?>

    <script>
        emailFlag = false;
        emailMessage = "";

        document.getElementById("resetData").addEventListener("click", function (event) {
            let el1 = document.getElementById("firstname");
            let el2 = document.getElementById("lastname");
            let el3 = document.getElementById("email");
            let el4 = document.getElementById("pass");
            let el5 = document.getElementById("confirm");

            el1.value = "";
            el2.value = "";
            el3.value = "";
            el4.value = "";
            el5.value = "";
        });
        
        document.getElementById("email").addEventListener("change", async function (event) {
            let email = document.getElementById("email").value.trim();

            let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (!emailRegex.test(email)) {
                emailFlag = false;
                emailMessage = "Invalid email";
                return;
            }

            if (email.length > 64) {
                emailFlag = false;
                emailMessage = "Email is too long, maximum 64 characters";
                return;
            }

            let res = await checkEmail(email);            
        
            if (res === "exists") {
                emailFlag = false;
                emailMessage = "This email is already in use";
                return;
            }
            else if (res === "error") {
                emailFlag = false;
                emailMessage = "Something went wrong, try again later";
                return;
            }
            else {
                emailFlag = true;
                emailMessage = "";
            }
        });

        document.getElementById("form").addEventListener("submit", function (event) {
            let firstname = document.getElementById("firstname").value.trim();
            let lastname = document.getElementById("lastname").value.trim();
            let email = document.getElementById("email").value.trim();
            let pass = document.getElementById("pass").value.trim();
            let confirm = document.getElementById("confirm").value.trim();

            if ((firstname === "") || lastname === "" || email === "" || pass === "" || confirm === "") {
                event.preventDefault();
                alert("You can't set an empty field");
            }

            if (firstname.length > 64) {
                event.preventDefault();
                alert("Firstname is too long, maximum 64 characters");
            }

            if (lastname.length > 64) {
                event.preventDefault();
                alert("Lastname is too long, maximum 64 characters");
            }

            if (!emailFlag) {
                event.preventDefault();
                alert(emailMessage);
            }

            if (pass !== confirm) {
                event.preventDefault();
                alert("Passwords don't match");
            }

            if (pass.length < 8) {
                event.preventDefault();
                alert("Password must be at least 8 characters long");
            }
        });    

        async function checkEmail (email) {
            try {
                const response = await fetch("./scripts/ajaxScripts/check_email.php", {
                    method: "POST",
                    headers: { 
                        "Content-Type": "application/x-www-form-urlencoded", 
                        "X-Requested-With": "XMLHttpRequest"
                    },
                    body: "email=" + encodeURIComponent(email),
                });

                if (!response.ok) 
                    throw new Error("Something went wrong, try again later");

                const data = await response.text();

                console.log(data);

                if (data === "exists") {
                    console.log("This email is already in use");
                    return data;
                }
            } catch (error) {
                console.error(error);
                return "error";
            }
        };
    </script>

</body>
</html>