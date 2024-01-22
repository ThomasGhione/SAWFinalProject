<?php 
    require("./php/shared/initializePage.php");

    require("./php/shared/banCheck.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("./php/shared/commonHead.php"); ?>

    <script src="https://kit.fontawesome.com/e856a5c7fb.js" crossorigin="anonymous"></script>

    <title>OpenHub Homepage</title>
</head>

<body>
    <?php require("./php/shared/nav.php"); ?>

    <main class="mainContainer">

        <div class="homepage-img-container">
            <img src="./images/bestLogo.png" alt="BESTLOGO">
        </div>

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

    <script>
        // JS Logic for cookie data
        document.querySelector('.fa-cookie').addEventListener('click', function() {
            var expires = new Date();
            expires.setMinutes(expires.getMinutes() + 30);
            var cookieValue = "A fantastic cookie, given by the awesome OpenHub website"
            var cookieName = "awesomeCookieFromOpenHub";
            document.cookie = cookieName + "=" + encodeURIComponent(cookieValue) + "; expires" + expires.toUTCString() + "; path=/";
            alert("You received a cookie! It will expire in half an hour!");
        });
    </script>

</body>
</html>