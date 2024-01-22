<?php 
    require("../shared/initializePageAdmin.php");

    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../index.php");
        exit;
    }

    if (!isset($_GET["email"]) || empty($_GET["email"]) || !filter_var(urldecode($_GET["email"]), FILTER_VALIDATE_EMAIL)) {
        header("Location: ./manageUsers.php");
        exit;
    }

    require_once("../phpClasses/loggedUser.php");

    $email = urldecode($_GET["email"]);

    $currentUser = new loggedUser($email);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("../shared/commonHead.php") ?>
    <title>OpenHub - Update user page</title>
</head>
<body>
    <?php include("../shared/nav.php"); ?>

    
    <main class="mainContainer">

        <fieldset class="formSection">
            <h2>Edit profile here:</h2>
                        
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

            <form id="form" action="./adminScripts/editUser.php" method="post">
                
                <div class="inputBox">
                    <label for="firstname">Firstname: </label>
                    <input type="text" id="firstname" name="firstname" value="<?php echo $currentUser->getFirstname() ?>" placeholder="Firstname">
                </div>
                
                <div class="inputBox">
                    <label for="lastname">Lastname: </label>
                    <input type="text" id="lastname" name="lastname" value="<?php echo $currentUser->getLastname() ?>" placeholder="Lastname">
                </div>

                <div class="inputBox">
                    <label for="newEmail">Email: </label>
                    <input type="email" id="newEmail" name="email" value="<?php echo $currentUser->getEmail() ?>" placeholder="Email">
                </div>

                <div class="inputBox">
                    <label for="pass">Password: </label>
                    <input type="password" id="pass" name="pass" placeholder="Password">
                </div>

                <div class="inputBox">
                    <label for="permission">Role: </label>
                    <select id="permission" name="permission">
                        <option value="user" <?php echo $currentUser->getPermission() === "user" ? "selected" : ""; ?> >User</option>
                        <option value="admin" <?php echo $currentUser->getPermission() === "admin" ? "selected" : ""; ?> >Admin</option>
                    </select>
                </div>

                <input type="hidden" name="userEmail" value="<?php echo htmlspecialchars($_GET["email"])?>">

                <input type="submit" class="formButton" name="submit" value="Edit">
            </form>

            <i><a href="./manageUsers.php" class="formButton">Go back to users list</a><i class="blankSpace"></i><button id="resetData" class="formButton">Reset Form</button></i>

        </fieldset>

    </main>


    <?php include("../shared/footer.php"); ?>

    <script>
        const currentFirstname = document.getElementById("firstname").value;
        const currentLastname = document.getElementById("lastname").value;
        const currentEmail = document.getElementById("newEmail").value;
        const currentPermission = document.getElementById("permission").value;

        document.getElementById("resetData").addEventListener("click", function (event) {
            let el1 = document.getElementById("firstname");
            let el2 = document.getElementById("lastname");
            let el3 = document.getElementById("newEmail");
            let el4 = document.getElementById("permission");
            let el5 = document.getElementById("pass");

            el1.value = currentFirstname;
            el2.value = currentLastname;
            el3.value = currentEmail;
            el4.value = currentPermission;
            el5.value = "";
        });

        emailFlag = true;
        emailMessage = "";

        document.getElementById("form").addEventListener("submit", function (event) {
            let firstname = document.getElementById("firstname").value.trim();
            let lastname = document.getElementById("lastname").value.trim();
            let email = document.getElementById("newEmail").value.trim();
            let pass = document.getElementById("pass").value.trim();

            if (firstname === "" || lastname === "" || newEmail === "" || pass === "") {
                event.preventDefault();
                alert("You can't set an empty field");
            }
            else if ((pass.length < 8)) {
                event.preventDefault();
                alert("Password must be at least 8 characters long");
            }
            else if (!emailFlag) {
                event.preventDefault();
                alert(emailMessage);
            }
        });

        document.getElementById("newEmail").addEventListener("change", async function (event) {
            let email = document.getElementById("newEmail").value.trim();

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
                const response = await fetch("../scripts/ajaxScripts/check_email.php", {
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