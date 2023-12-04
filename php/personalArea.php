<?php
    require('./phpClasses/sessionManager.php');
    require("./scripts/errInitialize.php");

    $sessionManager = new sessionManager();

    $sessionManager->startSession();

    if ( !$sessionManager->isSessionSet() )
        header('Location: ./login.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>

    <link rel="stylesheet" type="text/css" href="../CSS/personalArea.css">
    <title>OpenHub - Personal Area</title>
</head>
<body>
    <?php include("shared/nav.php") ?>

    <div class="main_personalarea">
        <column id="left_column">
            <a href="/SAW/SAWFinalProject/index.php"><img class="pfp" src="/SAW/SAWFinalProject/images/bestLogo.png" alt="Website Logo"></a>

            <?php echo '<p>Welcome ' . $_SESSION['email'] . '</p>';?>
            
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



    <?php include("shared/footer.html") ?>
</body>
</html>