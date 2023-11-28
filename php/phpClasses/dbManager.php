<?php

    class dbManager {  
    
        private $dbServer = 'localhost';
        private $username = '';
        private $password = '';
        private $dbName = 'databaseUni';
   
        private $conn;

        static private $emailregex = "/\S+@\S+\.\S+/";
        
        function __construct() {
            if ( !( $this->conn = new mysqli($this->dbServer, $this->username, $this->password, $this->dbName) )) {
                error_log ('Error: cannot connect to database', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Connection Error' . mysqli_connect_error());
            }  
        }

        function __destruct() {
            $this->conn->close();
        }

        // Query functions //

        function dbQueryWithParams($stmtString, $paramsTypes, $params) {

            if ( !($stmt = $this->conn->prepare($stmtString)) ) {
                error_log('Error: cannot prepare the following query -> ' . $stmtString);
                die ('Server error' . $this->conn->error);
            }

            if ( count($params) != strlen($paramsTypes) ) {
                error_log('Error: number of parameters does not match the number of types');
                die ('Server error' . $this->conn->error);
            }

            if ( !($stmt->bind_param($paramsTypes, ...$params)) ) {
                error_log('Error: cannot bind the following parameters -> ' . $params);
                die ('Server error' . $this->conn->error);
            }

            if ( !($stmt->execute() ) ) {
                error_log('Error: cannot execute the following query -> ' . $stmtString);
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
                error_log('Error: cannot execute the following query -> ' . $stmtString);
                die ('Server error' . $this->conn->error);
            }

            return $result;
        }

        // User functions //

        function registerUser() {

            // TODO aggiungere messaggi contestuali per ogni errore

            if ( empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['confirmpwd'])) {
                error_log('Error: empty parameters');
                return false;
            }

            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirmpwd = trim($_POST['confirmPwd']);
            
            // TODO correggere regex per email
            
            if ( !preg_match($this->emailregex, $email) ) {
                error_log('Error: invalid email format');
                return false;
            
            }
            
            if ( $password != $confirmpwd ) {
                error_log('Error: passwords do not match');
                return false;
            }


            $result = $this->dbQueryWithParams('SELECT * FROM users WHERE email = ?', 's', $email);

            if ( $result->num_rows != 0 ) {
                error_log('Error: email already in use');
                return false;
            }
            
            $password = password_hash($password, PASSWORD_DEFAULT);
            $paramArr = array($firstname, $lastname, $email, $password);

            // TODO 
            $result = $this->dbQueryWithParams('INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)', 'ssss', $paramArr);
            

            return $result == 1;
        }

        function loginUser($email, $password) {

        }
        
        function allUsers() {

        }
        
        // Aux functions //







    }




?>