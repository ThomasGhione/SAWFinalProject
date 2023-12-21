<?php
    require_once("./scripts/errInitialize.php");
    require_once("./phpClasses/cookieManager.php");
    require_once("./phpClasses/sessionManager.php");
    require_once("./phpClasses/dbManager.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();

    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);

    if ($sessionManager->isSessionSet()) {
        header("Location: ./personalArea.php");
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
                if (isset($_SESSION["error"])) {
                    echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                    unset($_SESSION["error"]);
                }
                elseif (isset($_SESSION['success'])) {
                    echo "<p class='success'>" . $_SESSION["success"] . "</p>";
                    unset($_SESSION["success"]);
                }
                else
                    echo "<p class='error'>&nbsp;</p>"; 
            ?>

            <form action="./scripts/login.php" method="post">
                <div class="inputBox"> 
                    <label for="email">E-Mail:</label>
                    <input required type="email" name="email" placeholder="E-Mail (required)">
                </div>
                
                <div class="inputBox">
                    <label for="pass">Password:</label>
                    <input type="password" name="pass" placeholder="Password (required)">
                </div>

                <div class="inputBox">
                    <label for="rememberMe">Remember Me:</label>
                    <input type="checkbox" id="rememberMe" name="rememberMe" placeholder="RememberMe">
                </div>

                <input type="submit" class="formButton" name="submit" value="Login">
            </form>
        </fieldset>

    </main>


    <?php include("./shared/footer.php"); ?>
</body>
</html>