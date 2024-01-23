<?php
    require("./shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ./loginForm.php");
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
            ?>

            <form id="repoForm" action="./scripts/addNewRepo.php" method="post" enctype="multipart/form-data"> 
                
                <div class="inputBox">
                    <label for="reposName">Repository name: </label>
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

    <script>
        
        document.getElementById("repoForm").addEventListener("submit", function(event) {
            var reposName = document.getElementById("reposName").value;
            var file = document.getElementById("fileUpload").files[0];
            var fileName = file.name.substring(0, file.name.lastIndexOf(".zip"));

            if (reposName == "" || !file) {
                alert("One of the fields are empty");
                event.preventDefault();
            }
            else if (file.type !== "application/zip" && file.type !== "application/x-zip-compressed") {
                // application/zip are sent from linux, application/x-zip-compressed are sent from windows
                alert("Only .zip files are accepted");
                event.preventDefault();
            }
            else if (/[.,\/]/.test(reposName) || /[.,\/]/.test(fileName)) {
                alert("Repository name should not contain . / or ,");
                event.preventDefault();
            }
            else if ( /[.,\/]/.test(fileName)) {
                alert("File name should not contain . / or ,");
                event.preventDefault();
            }

        })

    </script>

</body>
</html>