<?php 
    require("./shared/initializePage.php");

    require_once("./phpClasses/loggedUser.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ./loginForm.php");
        exit;
    }

    require("./shared/banCheck.php");

    $loggedUser = new loggedUser($sessionManager->getEmail());

    $firstname = $loggedUser->getFirstname();
    $lastname = $loggedUser->getLastname();
    $email = $loggedUser->getEmail();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>
    <title>OpenHub - Update your profile page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>
    
    <main class="mainContainer">

        <fieldset class="formSection">
            <h2>Edit your profile here:</h2>
                        
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

            <form id="form" action="./scripts/update_profile.php" method="post">
                <div class="inputBox">
                    <label for="firstname">Firstname: </label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo "$firstname"?>" placeholder="Firstname">
                </div>
                
                <div class="inputBox">
                    <label for="lastname">Lastname: </label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo "$lastname"?>" placeholder="Lastname">
                </div>

                <div class="inputBox">
                    <label for="email">Email: </label>
                    <input type="email" id="email" name="email" value="<?php echo "$email"?>" placeholder="Email">
                </div>

                <input type="submit" id="submitButton" class="formButton" name="submit" value="Edit">
            </form>
            
            <div class="endFormButtons">
                <button id="resetData" class="formButton">Reset data</button>
                <button class="formButton"><a href="./show_profile.php">Return to the personal area</a></button>
            </div>
        </fieldset>

    </main>


    <?php include("./shared/footer.php"); ?>

    <script>
        const currentFirstname = document.getElementById("firstname").value.trim();
        const currentLastname = document.getElementById("lastname").value.trim();
        const currentEmail = document.getElementById("email").value.trim();

        document.getElementById("resetData").addEventListener("click", function (event) {
            let el1 = document.getElementById("firstname");
            let el2 = document.getElementById("lastname");
            let el3 = document.getElementById("email");

            el1.value = currentFirstname;
            el2.value = currentLastname;
            el3.value = currentEmail;
        });


        emailFlag = true;
        emailMessage = "";
        
        document.getElementById("form").addEventListener("submit", function (event) {
            let firstname = document.getElementById("firstname").value.trim();
            let lastname = document.getElementById("lastname").value.trim();
            let email = document.getElementById("email").value.trim();

            if (firstname === "" || lastname === "" || email === "") {
                event.preventDefault();
                alert("You can't set an empty field");
            }
            else if (!emailFlag) {
                event.preventDefault();
                alert(emailMessage);
            }
        });   

        document.getElementById("email").addEventListener("change", async function (event) {
            let email = document.getElementById("email").value.trim();

            let emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if (email === currentEmail) {
                emailFlag = true;
                emailMessage = "";
                return;
            }

            if (!emailRegex.test(email)) {
                emailFlag = false;
                emailMessage = "Invalid email";
                return;
            }

            let res = await checkEmail(email);            
        
            if (res === "exists") {
                emailFlag = false;
                emailMessage = "This email is already in use";
            }
            else if (res === "error") {
                emailFlag = false;
                emailMessage = "Something went wrong, try again later";
            }
            else {
                emailFlag = true;
                emailMessage = "";
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
                });                emailFlag = true;
                emailMessage = "";

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