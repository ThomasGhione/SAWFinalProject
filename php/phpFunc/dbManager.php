<?php

    class dbManager {  
    
        private $dbServer = 'localhost';
        private $username = '';
        private $password = '';
        private $dbName = 'databaseUni';
   
        private $conn;
        
        function __construct() {
            if ( !( $this->conn = mysqli_connect($this->dbServer, $this->username, $this->password, $this->dbName) )) {
                error_log ('Error: cannot connect to database', 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
                die ('Connection Error' . mysqli_connect_error());
            }  
        }

        // TODO function
        function dbQuery($stmtString, $params) {
            
            if ( !($stmt = $this->conn->prepare($stmtString)) ){
                error_log('Error: cannot prepare the following query -> ' . $stmtString);
                die ('Server error' . $this->conn->error);
            }
            
            $stmt->bind_param(...$params);

            $stmt->execute();

            $result = $stmt->get_result();

            $stmt->close();

            return $result;
        }
        


    }




?>