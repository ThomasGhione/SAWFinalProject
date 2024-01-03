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

        // To be used ONLY for queries without params (lighter on resources because statements are not necessary)
        function dbQueryWithoutParams($stmtString) {
            try {
                if (($result = $this->conn->query($stmtString)) == false) {
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
                    error_log("Email already in use", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Email already in use");
                }
    
                $paramArr = [$user->getEmail(), $user->getPassword(), $user->getFirstName(), $user->getLastName()];
                $result = $this->dbQueryWithParams('INSERT INTO users (email, password, firstname, lastname, username, permission, pfp, gender, birthday, description, newsletter) VALUES (?, ?, ?, ?, null, "user", null, "notSpecified", null, null, false)', 'ssss', $paramArr);
    
                if ($result != 1) {
                    error_log("cannot insert user into database (1 expected)", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, please try again later");
                }

                // TODO Add exceptions for mkdir and chmod
                // Creates a new directory for user's repos 
                $email = $user->getEmail();
                mkdir("../../repos/$email");
                chmod("../../repos/$email", 0766);
            }
            catch (Exception $e) {
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
                    error_log("Email not found", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Email not found, please try again");
                }
    
                $row = $result->fetch_assoc();
    
                if (!password_verify($user->getPassword(), $row["password"])) {
                    error_log("Wrong password", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Wrong password. please try again");
                }
    
                if ($user->getRemMeFlag()) { 
                    $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? && (STR_TO_DATE(ExpDate, '%Y-%m-%d') < CURDATE()))", "s", [$user->getEmail()]);
    
                    $actTime = time();
                    $oneWeek = 604800; // 60 * 60 * 24 * 7 = 604800 seconds = 1 week
                    $expDate = date("Y-m-d", $actTime + $oneWeek);
                    $salt = "WeLoveRibaudo";
                    $UID = hash("sha512", (bin2hex(random_bytes(32)) . $actTime . $salt));
                    
                    $paramArr = [$UID, $user->getEmail(), $expDate];
                    $result = $this->dbQueryWithParams("INSERT INTO remMeCookies (UID, email, ExpDate) VALUES (?, ?, ?)", "sss", $paramArr);
    
                    if ($result != 1) {
                        error_log("Something went wrong in INSERT INTO (1 expected), try again later", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                        throw new Exception("Something went wrong when inserting the new cookie data");
                    }
    
                    $cookieManager = new cookieManager();
                    $cookieManager->setCookie("remMeCookie", $UID, $oneWeek);
                }
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                header("Location: ../loginForm.php");
                exit;
            }

            $user->setPermission($row["permission"]);
            $user->setNewsletter($row["newsletter"]);
            $_SESSION["success"] = "Login successful";
            return true;
        }


        function editProfile($email, $sessionManager) {

            // Sets data names and data 
            $dataTypeToUpdate = "";
            $dataToUpdate = array(); 
            $isEmailModified = !empty($_POST["email"]);

            foreach ($_POST as $dataName => $data) {
                if (!empty($data)) {
                    $dataTypeToUpdate .= " " . $dataName . " = ?,";
                    array_push($dataToUpdate, trim(htmlspecialchars($data)));
                }
            }

            if ($isEmailModified) { // Following code checks if email has changed, if so, it checks if email is valid, if so it changes session data and everything related to that email 
                $newEmail = htmlspecialchars($_POST["email"]);
                
                $result = $this->dbQueryWithParams("SELECT email FROM users WHERE email = ?", "s", [$newEmail]);
                
                try {
                    if ($result->num_rows == 1) {
                        error_log("Email already exists", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                        throw new Exception("Email already exists, please try again");
                    }
                }
                catch (Exception $e) {
                    $_SESSION["error"] = $e->getMessage();
                    return false;
                }

                $sessionManager->setEmail($newEmail);

                // Updates all remember me cookies from current email to the new one, 
                // TODO ask if should delete them instead of updating them
                $result = $this->dbQueryWithParams("UPDATE remMeCookies SET email = ? WHERE email = ?", "ss", [$newEmail, $email]);
                $result = $this->dbQueryWithParams("UPDATE repos SET Owner = ? WHERE Owner = ?", "ss", [$newEmail, $email]);
                rename("../../repos/$email", "../../repos/$newEmail");
            }

            // cleans data to be used in query function
            $dataTypeToUpdate = str_replace(", submit = ?,", "", $dataTypeToUpdate);
            array_pop($dataToUpdate);
            array_push($dataToUpdate, $email); // Adds last value to be used in query function

            // Sets data types for query function            
            $dataCount = "";
            for ($i = count($dataToUpdate); $i > 0; $i--) 
                $dataCount .= "s";

            // TODO Check result
            $result = $this->dbQueryWithParams("UPDATE users SET" . $dataTypeToUpdate . " WHERE email = ?", $dataCount, $dataToUpdate);

            return true;
        }
 

        // DB Repos Manipulation //

        function addNewRepo ($email) {
            $reposName = htmlspecialchars($_POST["reposName"]);
            $fileName = htmlspecialchars($_FILES["fileUpload"]["name"]);
            $pathLocation = "/SAW/SAWFinalProject/repos/$email/$reposName";
            $currentDate = date("Y-m-d", time());

            // TODO Check results
            $result = $this->dbQueryWithParams("INSERT INTO repos (Name, Owner, CreationDate, LastModified, RepoLocation) VALUES (?, ?, ?, ?, ?)", "sssss", [$reposName, $email, $currentDate, $currentDate, $pathLocation]);

            try {
                if (!mkdir("../../repos/$email/$reposName")) {
                    $error = error_get_last();
                    error_log($error["message"] . " Current value in pathLocation is: " . $pathLocation);
                    throw new Exception("Something went wrong, try again later");
                }

                chmod("../../repos/$email/$reposName", 0766);
    
                $tempPath = $_FILES["fileUpload"]["tmp_name"];


                if (!move_uploaded_file($tempPath, "../../repos/$email/$reposName/ . $fileName")) {
                    error_log("Something went wrong while transferring the file into its new location", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            return true;
        }

        function editRepo ($email) {

        }

        function deleteRepo ($email) {

        }

        // DB Cookie Manipulation //

        function addRememberMeCookieToDB($cookieManager) {

        }
        
        // Used for logout
        // TODO Must be finished, we need to add error checking and transaction managing
        function deleteRememberMeCookieFromDB($cookie, $email) {    
            $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? && UID = ?)", "ss", [$email, $cookie]);
            
            try {
                if ($result != 1) {
                    error_log("Couldn't delete the cookie from the database (0 found)", 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            return true;
        }

        function recoverSession($cookie, $session) {
            $cookieArr = explode(" ", $cookie); // boom!

            $result = $this->dbQueryWithParams("SELECT * FROM remMeCookies WHERE (UID = ? && (STR_TO_DATE(ExpDate, '%Y-%m-%d') > CURDATE()))", "s", [$cookieArr[0]]);
            
            if ($result->num_rows == 1) { // if we find it then we check when its expiring date, if it's not valid we delete it
                $row = $result->fetch_assoc();

                $result = $this->dbQueryWithParams("SELECT email, permission FROM users WHERE email = ?", "s", [$row["email"]]);
                $row = $result->fetch_assoc();
                    
                $session->setSessionVariables($row["email"], $row["permission"]);
            }
            
            // otherwise session won't be set
        }
            

        // Search Area tools //

        function searchUsers($userQuery) {
            
            $userQuery = "%" . $userQuery . "%";
            $result = $this->dbQueryWithParams("SELECT email, firstname, lastname FROM users WHERE (email LIKE ? OR firstname LIKE ? OR lastname LIKE ?)", "sss", [$userQuery, $userQuery, $userQuery]);

            if (!$result->num_rows) 
                echo "<h2>No users were found with these values</h2>";
            else {
                echo "
                    <table>
                    <caption> Users found </caption>
                    <thead>
                        <tr><th>Email</th><th>Firstname</th><th>Lastname</th></tr>
                    </thead>
                    <tbody>
                ";

                for ($colorFlag = true; $row = $result->fetch_assoc(); $colorFlag = !$colorFlag) {
                
                    if ($colorFlag)
                        echo "<tr class='oddRow'>";
                    else
                        echo "<tr class='evenRow'>";
                
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["firstname"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["lastname"]) . "</td>";

                    echo "</tr>";
                }

                echo "</tbody>
                    </table>
                ";     
            }       
        }

        function searchRepos($repoQuery) {
                        
            $repoQuery = "%" . $repoQuery . "%";
            $result = $this->dbQueryWithParams("SELECT Name, Owner, CreationDate, LastModified FROM repos WHERE (Owner LIKE ? OR Name LIKE ?)", "ss", [$repoQuery, $repoQuery]);

            if (!$result->num_rows) 
                echo "<h2>No repos were found with these values</h2>";
            else {
                echo "
                    <table>
                    <caption> Users found </caption>
                    <thead>
                        <tr><th>Email</th><th>Firstname</th><th>Lastname</th></tr>
                    </thead>
                    <tbody>
                ";

                for ($colorFlag = true; $row = $result->fetch_assoc(); $colorFlag = !$colorFlag) {
                
                    if ($colorFlag)
                        echo "<tr class='oddRow'>";
                    else
                        echo "<tr class='evenRow'>";
                
                    echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["firstname"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["lastname"]) . "</td>";

                    echo "</tr>";
                }

                echo "</tbody>
                    </table>
                ";
            }        
        }


        // Admin Tools //

        function manageUsers() {
            
            $result = $this->dbQueryWithoutParams("SELECT * FROM users");
           
            echo "
                <table>
                <caption> <h2>All Users</h2> </caption>
                <thead>
                    <tr><th>Firstname</th><th>Lastname</th><th>Email</th><th>Permission</th><th>Delete User</th><th>Edit User</th></tr>
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
                echo "<td><a href='./adminScripts/deleteUser.php?email='" . urlencode(htmlspecialchars($row["email"])) . "'><i class='fa-solid fa-trash'></i></a></td>";
                echo "<td><a href='./editUserForm.php?email='" . urlencode(htmlspecialchars($row["email"])) . "'><i class='fa-solid fa-pencil'></i></a></td>";

                echo "</tr>";
            }

            echo "</tbody>
                </table>
            ";
        }

        function manageAdmins() {
            $result = $this->dbQueryWithoutParams("SELECT * FROM users WHERE permission = 'admin'");
           
            echo "
                <table>
                <caption> <h2>All Admins</h2> </caption>
                <thead>
                    <tr><th>Firstname</th><th>Lastname</th><th>Email</th></tr>
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

                echo "<td><input type='checkbox' name='userCheckbox[]' value='" . htmlspecialchars($row["email"]) . "'></td>";
                

                echo "</tr>";
            }

            echo "</tbody>
                </table>
            ";
        }

        function manageSubbedToNewsletter() {
            $result = $this->dbQueryWithoutParams("SELECT * FROM users WHERE newsletter = 1");
           
            echo "
                <table>
                <caption> <h2>All Users</h2> </caption>
                <thead>
                    <tr><th>Firstname</th><th>Lastname</th><th>Email</th></tr><tr><th>Send Email</th></tr>
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

                echo "<td><input type='checkbox' name='userCheckbox[]' value='" . htmlspecialchars($row["email"]) . "'></td>";
                // echo "<label for = 'userCheckbox[]'>Send Email</label></td>";

                echo "</tr>";
            }

            echo "</tbody>
                </table>
            ";
        }

        function deleteUser($userEmail) {
            
            // TODO Controllare funzionamento
            
            $this->conn->begin_transaction();

            try {
                $result = $this->dbQueryWithParams("DELETE FROM users WHERE email=?", "s", [$userEmail]);

                if ($result != 1) {
                    error_log("Something went wrong when deleting a user, probably user wasn't defined, see final error: " . error_get_last(), 3, "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong when trying to delete user, see log file to know more");
                }
            } 
            catch (Exception $e) {
                $this->conn->rollback();
                
                $_SESSION["error"] = $e->getMessage();
                
                return false;
            }
            
            return true;
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


        function banUser($userEmail) {
            $result = $this->dbQueryWithParams("UPDATE users SET permission = 'banned' WHERE email = ?", "s", [$userEmail]);
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