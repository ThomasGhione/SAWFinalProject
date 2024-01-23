<?php     
    require_once("../../shared/initializePage.php");

    // Can't use session control because this script can be called also from Registration page

    if(($_SERVER["REQUEST_METHOD"] !== "POST") || (empty($_SERVER["HTTP_X_REQUESTED_WITH"])) || !isset($_POST["email"]) || (empty($_POST["email"])) || !filter_var(urldecode($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        echo "Invalid request";
        exit;
    }

    $email = urldecode($_POST["email"]);

    echo $dbManager->emailExists($email) ? "exists" : "notExists";
    
    exit;
?>