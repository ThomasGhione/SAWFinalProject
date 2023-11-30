<?php

    require_once('user.php');

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

            if (!($stmt = $this->conn->prepare($stmtString))) {
                error_log('Error: cannot prepare the following query -> ' . $stmtString, 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Server error' . $this->conn->error);
            }

            if (count($params) != strlen($paramsTypes)) {
                error_log('Error: number of parameters does not match the number of types', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Server error' . $this->conn->error);
            }

            if (!($stmt->bind_param($paramsTypes, ...$params))) {
                error_log('Error: cannot bind the following parameters -> ' . $params, 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Server error' . $this->conn->error);
            }

            if (!($stmt->execute())) {
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

            if (($result = $this->conn->query($stmtString)) === false) {
                error_log('Error: cannot execute the following query -> ' . $stmtString, 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Server error' . $this->conn->error);
            }

            return $result;
        }

        // User functions //

        function registerUser($user) {

            $result = $this->dbQueryWithParams('SELECT * FROM users WHERE email = ?', 's', [$user->getEmail()]);

            if ($result->num_rows != 0) {
                error_log('Error: email already in use', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Email already in use';
                return false;
            }

            $paramArr = [$user->getEmail(), $user->getPassword(), $user->getFirstName(), $user->getLastName(), $user->getUsername(), $user->getGender(), $user->getBirthday()];
 
            $result = $this->dbQueryWithParams('INSERT INTO users (email, password, firstname, lastname, username, permission, pfp, gender, birthdate, description) VALUES (?, ?, ?, ?, ?, "user", null, ?, ?, null)', 'sssssss', $paramArr);

            if ($result != 1) {
                error_log('Error: cannot insert user into database', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Something went wrong, try again later';
                return false;
            }

            $_SESSION['success'] = 'Registration Completed, please login to access the website';
            return true;
        }

        function loginUser($user) {

            $result = $this->dbQueryWithParams('SELECT * FROM users WHERE email = ?', 's', [$user->getEmail()]);

            if ( $result->num_rows != 1 ) {
                error_log('Error: email not found', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                $_SESSION['error'] = 'Email not found';
                return false;
            }

            $row = $result->fetch_assoc();

            if (!password_verify($user->getPassword(), $row['password'])) {
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