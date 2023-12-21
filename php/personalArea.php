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
            
            <!-- The following code will set user data obtained from database -->
            <?php 
            
                $currentUser = new loggedUser($sessionManager->getEmail());
            
            ?>
        
            <a href="/SAW/SAWFinalProject/index.php"><img class="pfp" src="/SAW/SAWFinalProject/images/bestLogo.png" alt="Website Logo"></a>

            <?php echo "<p>Welcome " . $_SESSION["email"] . "</p>";?>
            <?php
                if(isset($_COOKIE["remMeCookie"]))
                    echo "Cookie data: " . $_COOKIE["remMeCookie"];
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
            
            <a class="personalAreaButton" href="./editProfile.php">Edit your profile</a>

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