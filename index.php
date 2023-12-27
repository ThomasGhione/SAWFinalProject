<?php 
    require_once("./php/scripts/errInitialize.php");
    require_once("./php/phpClasses/sessionManager.php");
    require_once("./php/phpClasses/cookieManager.php");
    require_once("./php/phpClasses/dbManager.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();

    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("./php/shared/commonHead.php"); ?>
    <title>OpenHub Homepage</title>
</head>

<body>
    <?php include("./php/shared/nav.php"); ?>

    <div class="bg-image"></div>

    <main class="mainContainer">

        <section class="column">
            <img src="./images/bestLogo.png" alt="BESTLOGO">
        </section>

        <section class="column">
            <div class="second_column">
                <h2>WE PRESENT YOU OPENHUB</h2>
                
                <p>The leading (maybe not) platform for open source projects.<p>
                <p>Here you can look at others's repos because we believe in open source projects.</p>
                <p>(don't even think to make your repos as private)</p>
                <br>
                <p>Here's a cookie for you, just click on it: <span class="fa-solid fa-cookie fa-xl"></span></p> 
            </div>
        </section>

    </main>


    <?php include("./php/shared/footer.php") ?>
</body>
</html>