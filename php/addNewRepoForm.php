<?php
    require("./shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../index.php");
        exit;
    }

    require("./shared/banCheck.php");
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

            <form id="repoForm" action="./scripts/addNewRepo.php" method="post" enctype="multipart/form-data"> 
                
                <div class="inputBox">
                    <label for="reposName">Repository name: </label>
                    <input required type="text" id="reposName" name="reposName" placeholder="Name of the new repository" title="Repository name should not contain ., /, or ,">
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

    <script>
        
        document.getElementById("repoForm").addEventListener("submit", function(event) {
            var reposName = document.getElementById("reposName").value;

            if (/[.,\/?]/.test(reposName)) {
                event.preventDefault();
                alert("Repository name should not contain . / or ,")
            }

        })

    </script>

</body>
</html>