<?php 
    require("./shared/initializePage.php");

    if (!$sessionManager->isSessionSet() || !$sessionManager->isAdmin()) {
        header("Location: ../../index.php");
        exit;
    }

    require("./shared/banCheck.php");

    if (!isset($_GET["name"]) || empty(urldecode($_GET["name"]))) {
        header("Location: ./manageUsers.php");
        exit;
    }

    $email = urldecode($_GET["name"]);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>
    <title>OpenHub - Update repo page</title>
</head>
<body>
    <?php include("./shared/nav.php"); ?>

    
    <main class="mainContainer">

        <fieldset class="formSection">
            <h2>Edit repo here:</h2>
                        
            <?php
                if (isset($_SESSION["error"])) {
                    echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                    unset($_SESSION["error"]);
                }
                elseif (isset($_SESSION["success"])) {
                    echo "<p class='success'>" . $_SESSION["success"] . "</p>";
                    unset($_SESSION["success"]);
                }
            ?>

            <form action="./scripts/updateRepo.php" method="post" enctype="multipart/form-data">
                <div class="inputBox">
                    <label for="fileUpload">Update your repo (only .zip files are accepted): </label>
                    <input required type="file" id="fileUpload" name="fileUpload">
                </div>
            
                <input type="hidden" name="repoToEdit" value="<?php echo htmlspecialchars($_GET["name"])?>">

                <input type="submit" class="formButton" name="submit" value="Edit">
            </form>

            <a href="./show_profile.php" class="formButton">Go back to your personal area</a>

        </fieldset>

    </main>

    <?php include("./shared/footer.php"); ?>
</body>
</html>