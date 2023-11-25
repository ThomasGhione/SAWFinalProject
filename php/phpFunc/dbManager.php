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

        function dbQuery($stmtString, $paramsTypes, $params) {
            
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

        function closeConnection() {
            $this->conn->close();
        }

        function registerUser() {

        }

        function loginUser() {

        }
        


    }




?>