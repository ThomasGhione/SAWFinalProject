<?php 
    require("../shared/initializePage.php");

    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../index.php");
        exit;
    }

    if (!isset($_GET["email"]) || empty($_GET["email"]) || !filter_var(urldecode($_GET["email"]), FILTER_VALIDATE_EMAIL)) {
        header("Location: ./manageUsers.php");
        exit;
    }

    $email = urldecode($_GET["email"]);
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

            <form action="./adminScripts/editUser.php" method="post">
                            
                <div class="inputBox">
                    <label for="firstname">Firstname: </label>
                    <input type="text" id="firstname" name="firstname" placeholder="Firstname">
                </div>
                
                <div class="inputBox">
                    <label for="lastname">Lastname: </label>
                    <input type="text" id="lastname" name="lastname" placeholder="Lastname">
                </div>

                <div class="inputBox">
                    <label for="newEmail">Email: </label>
                    <input type="email" id="newEmail" name="email" placeholder="Email">
                </div>

                <div class="inputBox">
                    <label for="pass">Password: </label>
                    <input type="password" id="pass" name="pass" placeholder="Password">
                </div>

                <div class="inputBox">
                    <label for="permission">Role: </label>
                    <select id="permission" name="permission">
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

                <input type="hidden" name="userEmail" value="<?php echo htmlspecialchars($_GET["email"])?>">

                <input type="submit" class="formButton" name="submit" value="Edit">
            </form>

            <a href="./manageUsers.php" class="formButton">Go back to users list</a>

        </fieldset>

    </main>


    <?php include("../shared/footer.php"); ?>
</body>
</html>