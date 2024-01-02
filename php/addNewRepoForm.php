<?php
    require_once("./scripts/errInitialize.php");
    require_once("./phpClasses/cookieManager.php");
    require_once("./phpClasses/sessionManager.php");
    require_once("./phpClasses/dbManager.php");

    $sessionManager = new sessionManager();
    $cookieManager = new cookieManager();
    $dbManager = new dbManager();

    if (!$sessionManager->isSessionSet() && $cookieManager->isCookieSet("remMeCookie")) 
        $dbManager->recoverSession($cookieManager->getCookie("remMeCookie"), $sessionManager);

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../index.php");
        exit;
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./shared/commonHead.php") ?>
    <title>OpenHub - Add a new repository</title>
</head>

<body>
    <?php include("./shared/nav.php"); ?>

    <main class="mainContainer">

        <fieldset class="formSection">
            <h2>Create a new repo</h2>

            <?php
                if (isset($_SESSION["error"])) {
                    echo "<p class='error'>" . $_SESSION["error"] . "</p>";
                    unset($_SESSION["error"]);
                }
                elseif (isset($_SESSION["success"])) {
                    echo "<p class='success'>" . $_SESSION["success"] . "</p>";
                    unset($_SESSION["success"]);
                }
                else
                    echo "<p class='error'>&nbsp;</p>"; 
            ?>

            <form action="./scripts/addNewRepo.php" method="post" enctype="multipart/form-data"> 
                
                <div class="inputBox">
                    <label for="reposName">Repos name: </label>
                    <input required type="text" id="reposName" name="reposName" placeholder="Name of the new repository">
                </div> 

                <div class="inputBox">
                    <label for="fileUpload">Upload your file (only .zip files are accepted): </label>
                    <input required type="file" id="fileUpload" name="fileUpload">
                </div>

                <input type="submit" class="formButton" name="submit" value="Add the new repository!">

            </form>

        </fieldset>


    </main>

    <?php include("./shared/footer.php") ?>
</body>
</html>