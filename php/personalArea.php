<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <link rel="stylesheet" type="text/css" href="../CSS/personalArea.css">
    <title>OpenHub - Personal Area</title>
</head>
<body>
    <?php include("shared/nav.php") ?>

    <div class="main_personalarea">
        <column id="left_column">
            <a href="/SAW/SAWFinalProject/index.php"><img class="pfp" src="/SAW/SAWFinalProject/images/bestLogo.png" alt="Website Logo"></a>

            <div class="infos">
                <p>Username</p>
                <p>email</p>
                <p>job</p>
            </div>


            <div class="list_of_badges">                
                <p>badge1</p>
                <p>badge2</p>
                <p>badge3</p>
            </div>
        </column>
        
        <column id="right_column">

            <section class="first_section">
                <div class="top_badges">
                    <p>badge1</p>
                    <p>badge2</p>
                    <p>badge3</p>
                    <p>badge4</p>
                    <p>badge5</p>
                </div>

                
                <div class="top_sponsors">
                    <p>sponsor1</p>
                    <p>sponsor2</p>
                    <p>sponsor3</p>
                    <p>sponsor4</p>
                    <p>sponsor5</p>
                </div>
            </section>

            <div class="top_repos">
                <p>top_repo1</p>
                <p>top_repo2</p>
                <p>top_repo3</p>
                <p>...</p>
            </div>

            <div class="second_section">          
                <p>repo1</p>
                <p>repo2</p>
                <p>repo3</p>
                <p>...</p>
            </div>


        </column>
    </div>



    <?php include("shared/footer.php") ?>
</body>
</html>