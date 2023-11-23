<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="./CSS/style.css">
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

        <section class="column">   
            <?php include ("./php/shared/registrationForm.php")?>
        </section>

    </main>



    <?php include("./php/shared/footer.php") ?>
</body>
</html>