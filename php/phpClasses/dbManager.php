<?php

    class dbManager {  
    
        private $dbServer = 'localhost';
        private $username = '';
        private $password = '';
        private $dbName = 'databaseUni';
   
        private $conn;
        
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

            // TODO Controllare meglio numero parametri

            if ( !($stmt = $this->conn->prepare($stmtString)) ) {
                error_log('Error: cannot prepare the following query -> ' . $stmtString);
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

            if ( str_contains($stmtString, 'SELECT') )
                $result = $stmt->get_result();
            else
                $result = $stmt->affected_rows;

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

            if ( !isset($_POST['firstName']) || !isset($_POST['lastName']) || !isset($_POST['email']) || !isset($_POST['password']) || !isset($_POST['confirmpwd']))
                return false;

            $firstname = trim($_POST['firstname']);
            $lastname = trim($_POST['lastname']);
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);
            $confirmpwd = trim($_POST['confirmPwd']);
            
            // TODO correggere regex per email
            
            if ( ( $password != $confirmpwd )
                || ( !preg_match("/\S+@\S+\.\S+/", $email) ) )
                return false;

            $result = $this->dbQueryWithParams('SELECT * FROM users WHERE email = ?', 's', $email);

            if ($result->num_rows > 0)
                return false;
            
            $password = password_hash($password, PASSWORD_DEFAULT);
            $paramArr = array($firstname, $lastname, $email, $password);

            // TODO 
            $result = $this->dbQueryWithParams('INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)', 'ssss', $paramArr);
            
            if ( $result != 1 )
                return false;            

            return true;
        }

        function loginUser($email, $password) {

        }
        
        function allUsers() {

        }
        
        // Aux functions //







    }




?>