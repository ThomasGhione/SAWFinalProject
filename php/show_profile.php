<?php
    require("./shared/initializePage.php");

    require_once("./phpClasses/loggedUser.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ./loginForm.php");
        exit;
    }

    $currentUser = new loggedUser($sessionManager->getEmail()); // sets user data obtained from database
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

            <div class="infos">
                <?php 
                    if (!($pfpHref = ($currentUser->getPfp())))
                        $pfpHref = "default.jpg";
                
                    echo "<img class='pfp' src='/SAW/SAWFinalProject/images/pfps/$pfpHref' alt='Your profile picture'>";

                    echo "<p>Welcome " . $currentUser->getFirstname() . " " . $currentUser->getLastname() . "</p>";
                    echo "<br>";
                    echo "<i class='fa-solid fa-square-envelope'>" . " " . $currentUser->getEmail() . "</i>";
                ?>
            </div>

            <div class="list_of_badges">                
                <p>badge1</p>
                <p>badge2</p>
                <p>badge3</p>
            </div>
            
            <a class="personalAreaButton" href="./update_profile_form.php">Edit your profile</a>
            <a class="personalAreaButton" href="./addNewRepoForm.php">Add a new repo here!</a>
            
            <?php
                
                if (!$currentUser->getNewsletter())
                    echo "<a class='personalAreaButton' href='./scripts/manageUserInNewsletter.php?sub=" . "true" . "'>Subscribe to our newsletter!</a>";
                else
                    echo "<a class='personalAreaButton' href='./scripts/manageUserInNewsletter.php?sub=" . "false" . "'>Unsubscribe from our newsletter!</a>";

                echo "<a>Current value in newsletter is: " . $currentUser->getNewsletter() . "</a>";


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