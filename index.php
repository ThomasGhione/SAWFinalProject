<?php 
    require_once("./php/scripts/errInitialize.php");
    require_once('./php/phpClasses/sessionManager.php');

    $sessionManager = new sessionManager();

    $sessionManager->startSession();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once("./php/shared/commonHead.php"); ?>
    <title>OpenHub Homepage</title>
</head>

<body>
    <?php include("./php/shared/nav.php") ?>

    <div class="bg-image"></div>

    <main class="main_container">

        <section class="column">
            <img src="./images/bestLogo.png" alt="BESTLOGO">
        </section>

        <section class="column">
            <div class="second_column">
                <p>WE PRESENT YOU OPENHUB</p>
                <p>The leading (maybe not) platform for open source projects<p>
            </div>
        </section>

    </main>


    <?php include("./php/shared/footer.html") ?>
</body>
</html>