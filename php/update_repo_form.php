<?php 
    require("./shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ./loginForm.php");
        exit;
    }

    require("./shared/banCheck.php");

    if (!isset($_GET["name"]) || empty(urldecode($_GET["name"]))) {
        header("Location: ./manageUsers.php");
        exit;
    }
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

            <form id="form" action="./scripts/updateRepo.php" method="post" enctype="multipart/form-data">
                <div class="inputBox">
                    <label for="fileUpload">Update your repo (only .zip files are accepted): </label>
                    <input required type="file" id="fileUpload" name="fileUpload">
                </div>
            
                <input type="hidden" name="repoToEdit" value="<?php echo htmlspecialchars(urldecode($_GET["name"]))?>">

                <input type="submit" class="formButton" name="submit" value="Edit">
            </form>

            <a href="./show_profile.php" class="formButton">Go back to your personal area</a>

        </fieldset>

    </main>

    <?php include("./shared/footer.php"); ?>

    <script>
        document.getElementById("form").addEventListener("submit", function (event) {
            var file = document.getElementById("fileUpload").files[0];

            if (!file) {
                alert("You must upload a file");
                event.preventDefault();
            }
            else if (file.type !== "application/zip" && file.type !== "application/x-zip-compressed") {
                alert("Only .zip files are accepted");
                event.preventDefault();
            }
        });
    </script>
</body>
</html>