<?php 
    require("./shared/initializePage.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php require("./shared/commonHead.php"); ?>
    <title>OpenHub - Terms and Conditions</title>
    <link rel="stylesheet" type="text/css" href="../CSS/termsAndConditionsStyle.css">
</head>
<body>
    <?php require("./shared/nav.php"); ?>
    
    <main class="mainContainer">
        <div class="termsAndConditions">
            <h1>Terms and Conditions</h1>

            <p class="firstMessage">
                Welcome to our website. If you continue to browse and use this website, you are agreeing to comply with and be bound by the following terms and conditions of use, 
                which together with our privacy policy govern OpenHub's relationship with you in relation to this website.
            </p>

            <br>

            <p class="firstMessage">The use of this website is subject to the following terms of use:</p>

            <ol>
                <li>
                    The content of the pages of this website is for your general information and use only. It is subject to change without notice.
                </li>
                <li>
                    This website uses cookies to monitor browsing preferences. If you do allow cookies to be used,
                    the following personal information may be stored by us for use by third parties.
                </li>
                <li>
                    Neither we nor any third parties provide any warranty or guarantee as to the accuracy, timeliness, performance, completeness, 
                    or suitability of the information and materials found or offered on this website for any particular purpose. 
                    You acknowledge that such information and materials may contain inaccuracies or errors, 
                    and we expressly exclude liability for any such inaccuracies or errors to the fullest extent permitted by law.
                </li>
                <li>
                    Your use of any information or materials on this website is entirely at your own risk, for which we shall not be liable. 
                    It shall be your own responsibility to ensure that any products, services, or information available through this website meet your specific requirements.
                </li>
            </ol>

            <button onclick="window.history.back();">Go Back</button>
        </div>
    </main>

    <?php include("./shared/footer.php") ?>

</body>
</html>