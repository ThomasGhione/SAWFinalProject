<?php     
    require_once("../../shared/initializePage.php");

    if (!$sessionManager->isSessionSet()) {
        header("Location: ../../loginForm.php");
        exit;
    }

    if(($_SERVER["REQUEST_METHOD"] !== "POST") || (empty($_SERVER["HTTP_X_REQUESTED_WITH"])) || !isset($_POST["email"]) || (empty($_POST["email"])) || !filter_var(urldecode($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        echo "Invalid request";
        exit;
    }

    $email = urldecode($_POST["email"]);

    if ($dbManager->emailExists($email)) 
        echo "exists";
    else
        echo "notExists";

    unset($dbManager);
    exit;
?>