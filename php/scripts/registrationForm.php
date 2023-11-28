<?php
    require_once 'dbManager.php';

    $dbManager = new dbManager();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = trim($_POST['email']);

        if (doesUserExist($email, $dbManager)) {
            error_log('Error: email already in use', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
            $_SESSION["error"] = "Email already in use";
            header("Location: ../registration.php");
        }
        else {
            if ($dbManager->registerUser()) {
                $_SESSION["success"] = "Registration successful";
                header("Location: ../login.php");
            }
            else {
                $_SESSION["error"] = "Registration failed";
                header("Location: ../registration.php");
            }
        }
    }


    function doesUserExist($email, $dbManager) {
        $stmtString = "SELECT * FROM users WHERE email = ?";
        $paramsTypes = "s";
        $params = array($email);
        $result = $dbManager->dbQueryWithParams($stmtString, $paramsTypes, $params);
        return $result->num_rows != 0;
    }

?>