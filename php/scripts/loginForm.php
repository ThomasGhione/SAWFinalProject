<?php
    session_start();
    
    require_once('../phpClasses/dbManager.php');
    require_once('../phpClasses/sessionManager.php');


    $dbManager = new dbManager();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        if ($dbManager->loginUser($email, $password)) {
            $_SESSION['success'] = 'Login successful';
            header('Location: ../personalArea.php');
        }

    }

?>