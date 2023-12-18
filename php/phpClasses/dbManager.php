<?php

    require_once("user.php");
    require_once("cookieManager.php");
    require_once("sessionManager.php");

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


            // TODO Work in progress - Testing in progress 
            if ($user->getRemMeFlag()) { 
                
                // Checking if user already has cookies in DB, and then checking for expired cookies
                if($row["remMeFlag"] === 1) {
                    
                    $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? && (STR_TO_DATE(ExpDate, '%Y-%m-%d') < CURDATE()))", "s", [$user->getEmail()]);
                    
                    /*
                    // We're expecting to have at least one cookie deleted (we're checking also for cookies in other devices) since if a user that has a true remMeFlag in DB, and tries to login when never he logged out, that means he has an old cookie not deleted
                    if ( $result < 1 )
                        return $this->manageError("something went wrong in 'checkAndDeleteOldCookiesInDB' function during login process", "Something went wrong, try again later");
                    */
                }
                
                if (!$row["remMeFlag"]) {
                    $result = $this->dbQueryWithParams("UPDATE users SET remMeFlag = 1 WHERE email = ?", "s", [$user->getEmail()]);

                    if ( $result != 1 ) 
                        return $this->manageError("something went wrong when setting the 'remember me' flag", "Something went wrong in UPDATE users, try again later");
                }

                // TODO Chiedere alla prof se lo dobbiamo trattare come se fosse una password (ovvero salvare in locale una versione non hashata, mentre sul server deve essere hashato, o meno)

                $actTime = time();
                $expDate = date("Y-m-d", $actTime + 60 * 60 * 7 * 24);
                $salt = "superSecretSalt";
                $UID = hash("sha256", ($actTime . $salt));
                

                $paramArr = [$UID, $user->getEmail(), $expDate];

                $result = $this->dbQueryWithParams("INSERT INTO remMeCookies (UID, email, ExpDate) VALUES (?, ?, ?)", "sss", $paramArr);

                if ($result != 1) 
                    return $this->manageError("something went wrong when inserting the new cookie data", "Something went wrong in INSERT INTO, try again later");

                $cookieManager = new cookieManager();

                $cookieValues = $UID;
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
        function deleteNotExpiredRememberMeCookieFromDB($cookie, $email) {
            
            $cookieResult = $this->dbQueryWithParams("SELECT * FROM remMeCookies WHERE email = ?", "s", [$email]);
            
            // User doesn't have more set cookies
            if ( $cookieResult->num_rows == 1 ) 
                $result = $this->dbQueryWithParams("UPDATE users SET remMeFlag = 0 WHERE email = ?", "s", [$email]);
        
            $cookieArr = explode(" ", $cookie);

            // User can have more cookies in the database (more devices are used)
            while ( $row = $cookieResult->fetch_assoc() ) 
                if ( $cookieArr[0] == $row["UID"] )
                    $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? && UID = ?)", "ss", [$email, $row["UID"]]);
        }

        // TODO Work in progress, check if this function is working
        function recoverSession($cookie, $session) {

            $cookieArr = explode(" ", $cookie);

            $result = $this->dbQueryWithParams("SELECT * FROM remMeCookies WHERE (UID = ? && (STR_TO_DATE(ExpDate, '%Y-%m-%d') > CURDATE()))", "s", [$cookieArr[0]]);

            // Se lo troviamo, allora dobbiamo controllare la data di scadenza del cookie, se questa non è valida allora si elimina il cookie
            if( $result->num_rows == 1 ) {
                $row = $result->fetch_assoc();

                $result = $this->dbQueryWithParams("SELECT email, permission FROM users WHERE email = ?", "s", [$row["email"]]);
                $row = $result->fetch_assoc();
                    
                $session->setSessionVariables($row["email"], $row["permission"]);
            }
            
            // Altrimenti la sessione non viene settata
        }
            


        function allUsers() {
            $result = $this->dbQueryWithNoParams('SELECT * FROM users');
            $colorFlag = true;

            echo "<table>
                <caption> <h2>All Users</h2> </caption>
                <thead>
                    <tr><th>Firstname</th><th>Lastname</th><th>Email</th><th>Permission</th></tr>
                </thead>
                <tbody>
            ";

            while ( $row = $result->fetch_assoc() ){
                if($colorFlag)
                    echo "<tr class='oddRow'>";
                else
                    echo "<tr class='evenRow'>";
                
                    echo "<td>" . htmlspecialchars($row['firstname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['lastname']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['permission']) . "</td>";
                echo "</tr>";

                $colorFlag = false;      
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
            error_log("Error: " . $logMessage, 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
            $_SESSION["error"] = $userMessage;
            return false;
        }

        function manageFatalError() {
            $lastError = error_get_last();
            if ($lastError["type"] === E_ERROR) { 
                error_log("Fatal error: " . $lastError["message"], 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                $_SESSION["error"] = "Unexpected error occured, try again later";
                return false; // fre non so perché tu l'abbia aggiunta in manageError ma nel dubbio la aggiungo anche io qui
            }
        }
    }
?>