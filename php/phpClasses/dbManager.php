<?php

    class dbManager {  
    
        // TODO Might be static in the future
        private $dbServer = 'localhost';
        private $username = 'root';
        private $password = '';
        private $dbName = 'DatabaseSAWFinalProject';
        
        // TODO correggere regex per email
        private $emailregex = '/^[_a-z0-9.-]+@[a-z0-9-]+(.[a-z]{2,3})$/';


        private $conn;
        
        function __construct() {
            if ( !( $this->conn = new mysqli($this->dbServer, $this->username, $this->password, $this->dbName) )) {
                error_log ('Error: cannot connect to database', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Connection Error' . mysqli_connect_error());
            }  
        }

        function __destruct() {
            if ( $this->conn ) 
                $this->conn->close();
        }

        // Query functions //

        function dbQueryWithParams($stmtString, $paramsTypes, $params) {

            if ( !($stmt = $this->conn->prepare($stmtString)) ) {
                error_log('Error: cannot prepare the following query -> ' . $stmtString, 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Server error' . $this->conn->error);
            }

            if ( count($params) != strlen($paramsTypes) ) {
                error_log('Error: number of parameters does not match the number of types', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Server error' . $this->conn->error);
            }

            if ( !($stmt->bind_param($paramsTypes, ...$params)) ) {
                error_log('Error: cannot bind the following parameters -> ' . $params, 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Server error' . $this->conn->error);
            }

            if ( !($stmt->execute() ) ) {
                error_log('Error: cannot execute the following query -> ' . $stmtString, 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Server error' . $this->conn->error);
            }


            $result = (str_contains($stmtString, 'SELECT'))
                ? $stmt->get_result()
                : $stmt->affected_rows;

            $stmt->close();

            return $result;
        }

        // To be used only for queries without params (lighter on resources because we don't need to prepare the statement)
        function dbQueryWithNoParams($stmtString) {

            if ( ($result = $this->conn->query($stmtString)) === false ) {
                error_log('Error: cannot execute the following query -> ' . $stmtString, 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Server error' . $this->conn->error);
            }

            return $result;
        }

        // User functions //

        function registerUser($firstName, $lastName, $email, $password, $confirmpwd, $userName, $gender, $birthdate) {

            // TODO aggiungere messaggi contestuali per ogni errore

            if ( empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmpwd) || empty($userName)) {
                error_log('Error: empty parameters');
                $_SESSION['error'] = 'Empty parameters passed to the form';
                return false;
            }
            
            if ( !preg_match($this->emailregex, $email) ) {
                error_log('Error: invalid email format', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Invalid Email format';
                return false;
            }

            if ( $password != $confirmpwd ) {
                error_log('Error: passwords do not match', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Passwords do not match';
                return false;
            }

            $result = $this->dbQueryWithParams('SELECT * FROM users WHERE email = ?', 's', [$email]);

            if ( $result->num_rows != 0 ) {
                error_log('Error: email already in use', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Email already in use';
                return false;
            }
            
            $password = password_hash($password, PASSWORD_DEFAULT);

            if ( $birthdate !== null ) 
                $birthdate = date('Y-m-d', strtotime($birthdate));

            $paramArr = [$email, $password, $firstName, $lastName, $userName, $gender, $birthdate];

            // TODO 
            $result = $this->dbQueryWithParams('INSERT INTO users (email, password, firstname, lastname, username, permission, pfp, gender, birthdate, description) VALUES (?, ?, ?, ?, ?, "user", null, ?, ?, null)', 'sssssss', $paramArr);

            if ($result != 1) {
                error_log('Error: cannot insert user into database', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Something went wrong, try again later';
                return false;
            }

            $_SESSION['success'] = 'Registration successful, go to login page to access your content';
            return true;
        }

        function loginUser($email, $password) {
            // TODO aggiungere messaggi contestuali per ogni errore

            if ( empty($email) || empty($password) ) {
                error_log('Error: empty parameters', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Empty parameters passed to the form';
                return false;
            }

            $email = trim($email);
            $password = trim($password);

            $result = $this->dbQueryWithParams('SELECT * FROM users WHERE email = ?', 's', $email);

            if ( $result->num_rows != 1 ) {
                error_log('Error: email not found', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Email not found';
                return false;
            }

            $row = $result->fetch_assoc();

            if ( !password_verify($password, $row['password']) ) {
                error_log('Error: wrong password', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Wrong password';
                return false;
            }

            $_SESSION['success'] = 'Login successful';
            return true;
        }
        
        function allUsers() {
            return $this->dbQueryWithNoParams('SELECT * FROM users');
        }
        
    }

?>