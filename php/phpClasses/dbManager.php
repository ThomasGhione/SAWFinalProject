<?php

    require_once('user.php');
    require_once("cookieManager.php");

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

            if ($result->num_rows != 0)
                return $this->manageError("email already in use", "Email already in use");

            $paramArr = [$user->getEmail(), $user->getPassword(), $user->getFirstName(), $user->getLastName()];
 
            $result = $this->dbQueryWithParams('INSERT INTO users (email, password, firstname, lastname, username, permission, remMeFlag, pfp, gender, birthdate, description) VALUES (?, ?, ?, ?, null, "user", false, null, "notSpecified", null, null)', 'ssss', $paramArr);

            if ($result != 1) 
                return $this->manageError("cannot insert user into database", "Something went wrong, try again later");

            $_SESSION['success'] = 'Registration Completed, please login to access the website';
            return true;
        }

        function loginUser($user) {

            $result = $this->dbQueryWithParams('SELECT * FROM users WHERE email = ?', 's', [$user->getEmail()]);

            if ( $result->num_rows != 1 ) 
                return $this->manageError("email not found", "Email not found");

            $row = $result->fetch_assoc();

            if (!password_verify($user->getPassword(), $row['password'])) 
                return $this->manageError("wrong password", "Wrong password");


            // TODO Work in progress 
            if ($user->getRemMeFlag()) { 
                $result = $this->dbQueryWithParams("UPDATE users SET remMeFlag = 1 WHERE email = ?", "s", [$user->getEmail()]);

                if ( $result != 1 ) 
                    return $this->manageError("something went wrong when setting the 'remember me' flag", "Something went wrong, try again later");

                // TODO Chiedere alla prof se lo dobbiamo trattare come se fosse una password (ovvero salvare in locale una versione non hashata, mentre sul server deve essere hashato, o meno)
                $randVal = rand();
                
                $UID = password_hash($randVal, PASSWORD_DEFAULT);
                $expDate = date("Y-m-d", time() + 31536000);

                $paramArr = [$UID, $user->getEmail(), $expDate];

                $result = $this->dbQueryWithParams("INSERT INTO remMeCookies (UID, email, ExpDate) VALUES (?, ?, ?)", "sss", $paramArr);

                if ($result != 1) 
                    return $this->manageError("something went wrong when inserting the new cookie data", "Something went wrong, try again later");

                $cookieManager = new cookieManager();

                $cookieValues = $randVal . " " . $expDate;
                $cookieManager->setCookie("remMeCookie", $cookieValues, $expDate);
            }


            $user->setPermission($row['permission']);

            $_SESSION['success'] = 'Login successful';
            return true;
        }

        function addRememberMeCookieToDB($cookieManager) {

        }
        
        // Used for logout
        // TODO Must be finished, we need to add error checking and transaction managing
        function deleteRememberMeCookieFromDB($cookie, $email) {
            
            $cookieResult = $this->dbQueryWithParams("SELECT * FROM remMeCookies WHERE email = ?", "s", [$email]);
            
            // User doesn't have more set cookies (probably gonna be eliminated for average performance reasons)
            if ( $cookieResult->num_rows == 1 ) 
                $result = $this->dbQueryWithParams("UPDATE users SET remMeFlag = 0 WHERE email = ?", "s", [$email]);
        
            $cookieArr = explode(" ", $cookie);

            // User can have more cookies in the database (more devices are used)
            while ( $row = $cookieResult->fetch_assoc() ) {
                if ( password_verify($cookieArr[0], $row["UID"]) ) {
                    $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? && UID = ?)", "ss", [$email, $row["UID"]]);
                }
            }
        }


        function allUsers() {
            $result = $this->dbQueryWithNoParams('SELECT * FROM users');
            $color = true;

            echo "<table>
                <caption> <h2>All Users</h2> </caption>
                <thead>
                    <tr><th>Firstname</th><th>Lastname</th><th>Email</th><th>Permission</th></tr>
                </thead>
                <tbody>
            ";

            while ( $row = $result->fetch_assoc() ){
                if($color)
                    echo "<tr class='oddRow'>";
                else
                    echo "<tr class='evenRow'>";
                
                    echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['permission']) . "</td>";
                echo "</tr>";

                $color = !$color;      
            }

            echo "</tbody>
                </table>
            ";
        }


        // TODO Da sistemare
        // Admin tools methods

        function createUser($data) {
            $result = $this->dbQueryWithParams("INSERT INTO users (firstname, lastname, email, password, permission) VALUES (?, ?, ?, ?, ?)", "sssss", [$data["firstname"], $data["lastname"], $data["email"], $data["password"], $data["permission"]]);
            $stmt = $this->conn->prepare($result);
            $password = password_hash($data["password"], PASSWORD_DEFAULT);
            $stmt->bind_param("sssss", $data["firstname"], $data["lastname"], $data["email"], $password, $data["permission"]);
            $stmt->execute();
        }

        function editUser($data) {
            $result = $this->dbQueryWithParams("UPDATE users SET firstname=?, lastname=?, password=?, permission=? WHERE email=?", "s", [$data["firstname"], $data["lastname"], $data["password"], $data["permission"], $data["email"]]);
            $stmt = $this->conn->prepare($result);
            $password = password_hash($data["password"], PASSWORD_DEFAULT);
            $stmt->bind_param("sssss", $data["firstname"], $data["lastname"], $data["email"], $password, $data["permission"]);
            $stmt->execute();
        }

        function deleteUser($userEmail) {
            $result = $this->dbQueryWithParams("DELETE FROM users WHERE email=?", "s", [$userEmail]);
            $stmt = $this->conn->prepare($result);
            $stmt->bind_param("s", $userEmail);
            $stmt->execute();
        }
        function banUser($userEmail) {
            $result = $this->dbQueryWithParams('UPDATE users SET permission = "banned" WHERE email = ?', 's', [$userEmail]);
            $stmt = $this->conn->prepare($result);
            $stmt->bind_param("s", $userEmail);
            $stmt->execute();   
        }



        // TODO Creare queste funzioni per snellire altri metodi di questo codice

        // Error methods

        function manageError($logMessage, $userMessage) {
            error_log("Error: " . $logMessage, 3, '/SAW/SAWFinalProject/texts/errorLog.txt');
            $_SESSION['error'] = $userMessage;
            return false;
        }

        function manageFatalError() {

        }
    }
?>