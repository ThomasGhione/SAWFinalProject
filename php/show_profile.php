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
        <div id="left_column">

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
            
            <div class="personalAreaOptions">
                <a class="personalAreaButton" href="./update_profile_form.php">Edit your profile</a>
                <a class="personalAreaButton" href="./update_profile_password_form.php">Change your password</a>
                <a class="personalAreaButton" href="./addNewRepoForm.php">Add a new repo here!</a>

                <?php
                    
                    if (!$currentUser->getNewsletter())
                        echo "<a class='personalAreaButton' href='./scripts/manageUserInNewsletter.php?sub=" . "true" . "'>Subscribe to our newsletter!</a>";
                    else
                        echo "<a class='personalAreaButton' href='./scripts/manageUserInNewsletter.php?sub=" . "false" . "'>Unsubscribe from our newsletter!</a>";

                    if (isset($_SESSION["error"])) {
                        echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                        unset($_SESSION["error"]);
                    }
                    elseif (isset($_SESSION['success'])) {
                        echo "<p class='success'>" . $_SESSION["success"] . "</p>";
                        unset($_SESSION["success"]);
                    }
                
                ?>
            </div>
        </div>
         
        <div id="right_column">

            <p>This part of the website is under construction</p>

        </div>
    </div>

    <?php include("shared/footer.php") ?>
</body>
</html>