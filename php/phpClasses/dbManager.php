<?php

    require_once("user.php");
    require_once("cookieManager.php");
    require_once("sessionManager.php");

    class dbManager {  
    
        // TODO Might be static in the future
        private $dbServer = "localhost";
        private $username = "root";
        private $password = "";
        private $dbName = "DatabaseSAWFinalProject";
        
        // TODO correggere regex per email
        private $emailregex = "/^[_a-z0-9.-]+@[a-z0-9-]+(.[a-z]{2,3})$/";


        private $conn;
        
        function __construct() {
            try {
                if (!($this->conn = new mysqli($this->dbServer, $this->username, $this->password, $this->dbName))) {
                    error_log("Error: cannot connect to database", 3 , "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Error: cannot connect to DB");
                }  
            }
            catch (Exception $e) {
                die ($e->getMessage() . mysqli_connect_error()); // TODO if die then create error page rather than BOOM the server
            }
        }

        function __destruct() {
            if ($this->conn) 
                $this->conn->close();
        }

        // Query functions //

        function dbQueryWithParams($stmtString, $paramsTypes, $params) {
            try {
                if (!($stmt = $this->conn->prepare($stmtString))) {
                    error_log("Error: cannot prepare the following query -> " . $stmtString, 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("server error");
                }
                if (count($params) != strlen($paramsTypes)) {
                    error_log("Error: number of parameters does not match the number of types", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("server error");
                }
                if (!($stmt->bind_param($paramsTypes, ...$params))) {
                    error_log("Error: cannot bind the following parameters -> " . $params, 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("server error");
                }
                if (!($stmt->execute())) {
                    error_log("Error: cannot execute the following query -> " . $stmtString, 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Server error");
                }
            }
            catch (Exception $e) { die($e->getMessage() . $this->conn->error); }


            $result = (str_contains($stmtString, "SELECT"))
                ? $stmt->get_result()
                : $stmt->affected_rows;

            $stmt->close();
            return $result;
        }

        // To be used only for queries without params (lighter on resources because we don't need to prepare the statement)
        function dbQueryWithoutParams($stmtString) {
            try {
                if (($result = $this->conn->query($stmtString)) === false) {
                    error_log("Error: cannot execute the following query -> " . $stmtString, 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Server error");
                }
            }
            catch (Exception $e) { die($e->getMessage() . $this->conn->error); }

            return $result;
        }


        // User functions //

        function registerUser($user) {

            $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$user->getEmail()]);


            try {
                if ($result->num_rows != 0) {
                    error_log("email already in use");
                    throw new Exception("email already in use");
                }
    
                $paramArr = [$user->getEmail(), $user->getPassword(), $user->getFirstName(), $user->getLastName()];
                $result = $this->dbQueryWithParams('INSERT INTO users (email, password, firstname, lastname, username, permission, remMeFlag, pfp, gender, birthdate, description) VALUES (?, ?, ?, ?, null, "user", false, null, "notSpecified", null, null)', 'ssss', $paramArr);
    
                if ($result != 1) {
                    error_log("cannot insert user into database");
                    throw new Exception("Something went wrong, please try again later");
                }
            }
            catch (Exception $e) {
                error_log($e->getMessage(), 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                $_SESSION["error"] = $e->getMessage();
                header("Location: ../loginForm.php");
                exit;
            }

            $_SESSION["success"] = "Registration Completed, please login to access the website";
            return true;
        }

        function loginUser($user) {

            $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$user->getEmail()]);
            
            try {
                if ($result->num_rows != 1) {
                    error_log("Email not found");
                    throw new Exception("Email not found");
                }
    
                $row = $result->fetch_assoc();
    
                if (!password_verify($user->getPassword(), $row['password'])) {
                    error_log("Wrong password");
                    throw new Exception("Wrong password");
                }
    
                // TODO Work in progress - Testing in progress 
                if ($user->getRemMeFlag()) { 
                    
                    if ($row["remMeFlag"] === 1) // Checking if user already has cookies in DB, and then checking for expired cookies
                        $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? && (STR_TO_DATE(ExpDate, '%Y-%m-%d') < CURDATE()))", "s", [$user->getEmail()]);
                    
                    if (!$row["remMeFlag"]) {
                        $result = $this->dbQueryWithParams("UPDATE users SET remMeFlag = 1 WHERE email = ?", "s", [$user->getEmail()]);
    
                        if ($result != 1) {
                            error_log("Something went wrong when setting the 'remember me' flag");
                            throw new Exception("Something went wrong in UPDATE users, try again later");
                        }
                    }
    
                    $actTime = time();
                    $oneWeek = 60 * 60 * 24 * 7;
                    $expDate = date("Y-m-d", $actTime + $oneWeek);
                    $salt = "WeLoveRibaudo";
                    $UID = hash("sha512", (bin2hex(random_bytes(32)) . $actTime . $salt));
                    
                    $paramArr = [$UID, $user->getEmail(), $expDate];
                    $result = $this->dbQueryWithParams("INSERT INTO remMeCookies (UID, email, ExpDate) VALUES (?, ?, ?)", "sss", $paramArr);
    
                    if ($result != 1) {
                        error_log("Something went wrong when inserting the new cookie data");
                        throw new Exception("Something went wrong in INSERT INTO, try again later");
                    }
    
                    $cookieManager = new cookieManager();
                    $cookieManager->setCookie("remMeCookie", $UID, $oneWeek);
                }
            }
            catch (Exception $e) {
                error_log($e->getMessage(), 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                $_SESSION["error"] = $e->getMessage();
                header("Location: ../loginForm.php");
                exit;
            }

            $user->setPermission($row["permission"]);
            $_SESSION["success"] = "Login successful";
            return true;
        }


        // DB Cookie Manipulation //

        function addRememberMeCookieToDB($cookieManager) {

        }
        
        // Used for logout
        // TODO Must be finished, we need to add error checking and transaction managing
        function deleteRememberMeCookieFromDB($cookie, $email) {

            $cookieResult = $this->dbQueryWithParams("SELECT * FROM remMeCookies WHERE email = ?", "s", [$email]);
            
            if ($cookieResult->num_rows == 1) // User doesn't have more set cookies
                $result = $this->dbQueryWithParams("UPDATE users SET remMeFlag = 0 WHERE email = ?", "s", [$email]);

            $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? && UID = ?)", "ss", [$email, $cookie]);
        }

        // TODO Work in progress, check if this function is working
        function recoverSession($cookie, $session) {

            $cookieArr = explode(" ", $cookie);

            $result = $this->dbQueryWithParams("SELECT * FROM remMeCookies WHERE (UID = ? && (STR_TO_DATE(ExpDate, '%Y-%m-%d') > CURDATE()))", "s", [$cookieArr[0]]);

            
            if($result->num_rows == 1) { // Se lo troviamo, allora dobbiamo controllare la data di scadenza del cookie, se questa non è valida allora si elimina il cookie
                $row = $result->fetch_assoc();

                $result = $this->dbQueryWithParams("SELECT email, permission FROM users WHERE email = ?", "s", [$row["email"]]);
                $row = $result->fetch_assoc();
                    
                $session->setSessionVariables($row["email"], $row["permission"]);
            }
            
            // Altrimenti la sessione non viene settata
        }
            

        // Admin Tools //

        function allUsers() {
            
            $result = $this->dbQueryWithoutParams("SELECT * FROM users");
           
            echo "<table>
                <caption> <h2>All Users</h2> </caption>
                <thead>
                    <tr><th>Firstname</th><th>Lastname</th><th>Email</th><th>Permission</th></tr>
                </thead>
                <tbody>
            ";

            for ($colorFlag = true; $row = $result->fetch_assoc(); $colorFlag = !$colorFlag) {
                
                if ($colorFlag)
                    echo "<tr class='oddRow'>";
                else
                    echo "<tr class='evenRow'>";
                
                echo "<td>" . htmlspecialchars($row["firstname"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["lastname"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["permission"]) . "</td>";

                echo "</tr>";
            }

            echo "</tbody>
                </table>
            ";
        }

        // TODO Da sistemare
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



        // Error methods //

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