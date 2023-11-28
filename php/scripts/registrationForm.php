<?php
    require_once('../phpClasses/dbManager.php');

    $dbManager = new dbManager();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $confirmpwd = trim($_POST['confirmPwd']);

        if ($dbManager->registerUser($firstname, $lastname, $email, $password, $confirmpwd))
            $_SESSION["success"] = "Registration successful, go to login page";
    }

    header ('Location: ../registration.php');

?>