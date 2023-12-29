<?php 
    require("./php/shared/initializePage.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("./php/shared/commonHead.php"); ?>
    <title>OpenHub Homepage</title>
</head>

<body>
    <?php require("./php/shared/nav.php"); ?>

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