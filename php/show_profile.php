<?php
    require_once("./scripts/errInitialize.php");
    require_once("./phpClasses/cookieManager.php");
    require_once("./phpClasses/sessionManager.php");
    require_once("./phpClasses/dbManager.php");
    require_once("./phpClasses/loggedUser.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();

    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);

    if (!$sessionManager->isSessionSet()) {
        header("Location: ./loginForm.php");
        exit;
    }

    // The following code sets user data obtained from database
    $currentUser = new loggedUser($sessionManager->getEmail());
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
        
            <?php 
                if (!($pfpHref = ($currentUser->getPfp())))
                    $pfpHref = "default.jpg";
                
                echo "<img class='pfp' src='/SAW/SAWFinalProject/images/pfps/$pfpHref' alt='Your profile picture'>";

            
                if ($currentUser->getUsername() == "")
                    echo "<p>Welcome " . $currentUser->getEmail() . "</p>";
                else
                    echo "<p>Welcome " . $currentUser->getUsername() . "</p>";
            ?>
            
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
            
            <a class="personalAreaButton" href="./update_profile_form.php">Edit your profile</a>

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