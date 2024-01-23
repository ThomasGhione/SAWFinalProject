<?php

    require_once("user.php");
    require_once("cookieManager.php");
    require_once("sessionManager.php");

    class dbManager {  
    
        private const DB_SERVER = "localhost";
        private const USERNAME = "root";
        private const PASSWORD = "";
        private const DB_NAME = "DatabaseSAWFinalProject";
        private const EMAIL_REGEX = "/^[_a-z0-9.-]+@[a-z0-9-]+(.[a-z]{2,3})$/";


        protected $conn;

        function __destruct() {
            $this->closeConn();
        }

        function activateConn() {
            try {
                if (!isset($this->conn))
                    if (!($this->conn = new mysqli(dbManager::DB_SERVER, dbManager::USERNAME, dbManager::PASSWORD, dbManager::DB_NAME))) {
                        error_log("Error: cannot connect to database", 3 , $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                        throw new Exception("Error: cannot connect to DB");
                    }
            }
            catch (Exception $e) { die ($e->getMessage() . mysqli_connect_error()); } // TODO if die then create error page rather than BOOM the server
        }

        function closeConn() {
            if ($this->conn) {
                $this->conn->close();
                $this->conn = null;
            }
        }


        // Query functions //
        function dbQueryWithParams(string $query, string $paramsTypes, array $params) {
            try {
                if (!($stmt = $this->conn->prepare($query))) {
                    error_log("Error: cannot prepare the following query -> " . $query, 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("server error");
                }
                if (count($params) != strlen($paramsTypes)) {
                    error_log("Error: number of parameters does not match the number of types", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("server error");
                }
                if (!($stmt->bind_param($paramsTypes, ...$params))) {
                    error_log("Error: cannot bind the following parameters -> " . $params, 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("server error");
                }
                if (!($stmt->execute())) {
                    error_log("Error: cannot execute the following query -> " . $query, 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Server error");
                }
            }
            catch (Exception $e) { die($e->getMessage() . $this->conn->error); }


            $result = (str_contains($query, "SELECT"))
                ? $stmt->get_result()
                : $stmt->affected_rows;

            $stmt->close();
            return $result;
        }

        // To be used ONLY for queries without params (lighter on resources because statements are not necessary)
        function dbQueryWithoutParams(string $query) {
            try {
                if (($result = $this->conn->query($query)) == false) {
                    error_log("Error: cannot execute the following query -> " . $query, 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Server error");
                }
            }
            catch (Exception $e) { die($e->getMessage() . $this->conn->error); }

            return $result;
        }


        // User functions //

        function registerUser($user): bool {
            try {
                $this->activateConn();

                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$user->getEmail()]);

                if ($result->num_rows != 0) {
                    error_log("Email already in use", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Email already in use");
                }
    
                $paramArr = [$user->getEmail(), $user->getPassword(), $user->getFirstName(), $user->getLastName()];
                $result = $this->dbQueryWithParams('INSERT INTO users (email, password, firstname, lastname, username, permission, pfp, gender, birthday, description, newsletter) VALUES (?, ?, ?, ?, null, "user", null, "notSpecified", null, null, false)', 'ssss', $paramArr);
    
                if ($result != 1) {
                    error_log("cannot insert user into database (1 expected)", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, please try again later");
                }

                // Creates a new directory for user's repos 
                $email = $user->getEmail();
                mkdir("../../repos/$email");
                chmod("../../repos/$email", 0777);
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $_SESSION["error"] = $e->getMessage();
                $this->closeConn();
                header("Location: ../registrationForm.php");
                exit;
            }

            $_SESSION["success"] = "Registration Completed, please login to access the website";
            
            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        function loginUser($user): bool {
            try {
                $this->activateConn();
                
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$user->getEmail()]);

                if ($result->num_rows != 1) {
                    error_log("Email not found", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Email not found, please try again");
                }
    
                $row = $result->fetch_assoc();
    
                if (!password_verify($user->getPassword(), $row["password"])) {
                    error_log("Wrong password", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Wrong password. please try again");
                }

                if ($this->isBanned($user->getEmail())) {
                    error_log("User is banned", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("You are banned. Please contact an admin if you think you didn't violate our terms and conditions");
                }
    
                if ($user->getRemMeFlag()) { 
                    $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? && (STR_TO_DATE(ExpDate, '%Y-%m-%d') < CURDATE()))", "s", [$user->getEmail()]);
    
                    $actTime = time();
                    $oneWeek = 604800; // 60 * 60 * 24 * 7 = 604800 seconds = 1 week
                    $expDate = date("Y-m-d", $actTime + $oneWeek);
                    $UID = hash("sha512", (bin2hex(random_bytes(32)) . $actTime));
                    
                    $paramArr = [$UID, $user->getEmail(), $expDate];
                    $result = $this->dbQueryWithParams("INSERT INTO remMeCookies (UID, email, ExpDate) VALUES (?, ?, ?)", "sss", $paramArr);
    
                    if ($result != 1) {
                        error_log("Something went wrong in INSERT INTO (1 expected), try again later", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                        throw new Exception("Something went wrong, try again later");
                    }
    
                    $cookieManager = new cookieManager();
                    $cookieManager->setCookie("remMeCookie", $UID, $oneWeek);
                }
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $_SESSION["error"] = $e->getMessage();
                $this->closeConn();
                header("Location: ../loginForm.php");
                exit;
            }

            $this->conn->commit();
            $this->closeConn();

            $user->setPermission($row["permission"]);
            $user->setNewsletter($row["newsletter"]);
            
            $_SESSION["success"] = "Login successful";
            return true;
        }


        // Checks whether the user is banned or not
        function isBanned(string $email): bool {
            
            $result = $this->dbQueryWithParams("SELECT permission FROM users WHERE email = ?", "s", [$email]);
            $permission = $result->fetch_assoc()["permission"];

            return ($permission == "banned");
        }

        // Editing profile functions (only for users)

        function editProfile(string $email, &$sessionManager): bool {
            try {
                $this->activateConn();
                
                $this->conn->begin_transaction();

                $newEmail = trim($_POST["email"]);
                $firstname = htmlspecialchars(trim($_POST["firstname"]));
                $lastname = htmlspecialchars(trim($_POST["lastname"]));

                $this->checkCommonEditData($email, $newEmail, $firstname, $lastname);

                $hasEmailChanged = ($email != $newEmail);

                $result = $this->dbQueryWithParams("UPDATE users SET email = ?, firstname = ?, lastname = ? WHERE email = ?", "ssss", [$newEmail, $firstname, $lastname, $email]);

                if ($result != 1){
                    error_log("Something went wrong while updating user's data from Manage Users page", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                // If email has changed, we need to update some data related to repos and cookies
                if ($hasEmailChanged) {

                    $sessionManager->setEmail($newEmail);

                    // Updates all remember me cookies from current email to the new one, 
                    $result = $this->dbQueryWithParams("UPDATE remMeCookies SET email = ? WHERE email = ?", "ss", [$newEmail, $email]);
                    $result = $this->dbQueryWithParams("UPDATE repos SET Owner = ? WHERE Owner = ?", "ss", [$newEmail, $email]);
                    rename("../../repos/$email", "../../repos/$newEmail");
                }
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        function updatePassword(string $email): bool {

            $this->activateConn();

            $this->conn->begin_transaction();

            $oldPassword = trim($_POST["oldPassword"]);
            $newPassword = trim($_POST["newPassword"]);

            try {
                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$email]);

                if ($result->num_rows != 1) {
                    error_log("Something went wrong while updating the pw. (result->num_rows expected to be 1)", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong while updating the password, please try again");
                }

                $row = $result->fetch_assoc();

                if (!password_verify($oldPassword, $row["password"])) {
                    error_log("Wrong password", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Wrong password, please try again");
                }

                if (strlen($newPassword) < 8) {
                    error_log("Password must be at least 8 characters long", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Password must be at least 8 characters long, please try again");
                }

                $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $result = $this->dbQueryWithParams("UPDATE users SET password = ? WHERE email = ?", "ss", [$newPassword, $email]);

                if ($result != 1) {
                    error_log("Something went wrong while updating the pw. (result expected to be 1)", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong while updating the password, please try again");
                }
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            $this->closeConn();
            return true;
        }
        

        // Session managing methods
        
        function deleteRememberMeCookieFromDB(string &$cookie, string $email): bool {    // Used for logout
            try {
                $this->activateConn();
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? && UID = ?)", "ss", [$email, $cookie]);

                if ($result != 1) {
                    error_log("Couldn't delete the cookie from the database (0 found)", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        function recoverSession(string &$cookie, &$session): void {
            $this->activateConn();
            
            $cookieArr = explode(" ", $cookie);

            $result = $this->dbQueryWithParams("SELECT * FROM remMeCookies WHERE (UID = ? && (STR_TO_DATE(ExpDate, '%Y-%m-%d') > CURDATE()))", "s", [$cookieArr[0]]);
            
            if ($result->num_rows == 1) { // if we find it then we check when its expiring date, if it's not valid we delete it
                $row = $result->fetch_assoc();

                $result = $this->dbQueryWithParams("SELECT email, permission FROM users WHERE email = ?", "s", [$row["email"]]);
                $row = $result->fetch_assoc();
                    
                $session->setSessionVariables($row["email"], $row["permission"]);
            }
            
            $this->closeConn();
            // otherwise session won't be set
        }
            

        // Search Area tools //

        function searchUsers(string &$userQuery): array {
            $this->activateConn();

            $userQuery = "%" . htmlspecialchars(trim($userQuery)) . "%";
            $result = $this->dbQueryWithParams("SELECT email, firstname, lastname FROM users WHERE (email LIKE ? OR firstname LIKE ? OR lastname LIKE ?)", "sss", [$userQuery, $userQuery, $userQuery]);
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();

            return $finalResult;      
        }

        function searchRepos(string &$repoQuery): array {
            $this->activateConn();
            
            $repoQuery = "%" . htmlspecialchars(trim($repoQuery)) . "%";
            $result = $this->dbQueryWithParams("SELECT Name, Owner, CreationDate, LastModified FROM repos WHERE (Owner LIKE ? OR Name LIKE ?)", "ss", [$repoQuery, $repoQuery]);
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();

            return $finalResult;
        }

        function showRepos(string $email): array {
            $this->activateConn();
            
            $result = $this->dbQueryWithParams("SELECT Name, CreationDate, LastModified FROM repos WHERE Owner = ?", "s", [$email]);
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();

            return $finalResult;
        }


        // DB Repos Manipulation //
        function addNewRepo(string $email): bool {
            $reposName = htmlspecialchars(trim($_POST["reposName"]));
            $fileName = htmlspecialchars(trim($_FILES["fileUpload"]["name"]));
            $pathLocation = "/SAW/SAWFinalProject/repos/$email/$reposName";
            $currentDate = date("Y-m-d", time());
        
            try {
                $this->activateConn();
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("INSERT INTO repos (Name, Owner, CreationDate, LastModified, RepoLocation) VALUES (?, ?, ?, ?, ?)", "sssss", [$reposName, $email, $currentDate, $currentDate, $pathLocation]);

                if ($result != 1) {
                    error_log("Repos could not be created in database", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                if (!mkdir("../../repos/$email/$reposName")) {
                    $error = error_get_last();
                    error_log($error["message"] . " Current value in pathLocation is: " . $pathLocation, 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }
        
                chmod("../../repos/$email/$reposName", 0766);
            
                $tempPath = $_FILES["fileUpload"]["tmp_name"];
        
                if (!move_uploaded_file($tempPath, "../../repos/$email/$reposName/$fileName")) {
                    error_log("Something went wrong while transferring the file into its new location", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                $_SESSION["error"] = $e->getMessage();
                return false;
            }
            
            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        function deleteRepo(string $email, string $repoName): bool {
            
            try{
                $this->activateConn();
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM repos WHERE (Owner = ? && Name = ?)", "ss", [$email, $repoName]);
                
                if ($result->num_rows != 1) {
                    error_log("User $email tried to delete a repo not of his own, or chosen user does not exist", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                $result = $this->dbQueryWithParams("DELETE FROM repos WHERE (Owner = ? && Name = ?)", "ss", [$email, $repoName]);

                if ($result != 1) {
                    error_log("Something went wrong while deleting the repo from the database", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                if (!$this->deleteDirectory("../../repos/$email/$repoName")) {
                    error_log("Something went wrong when trying to delete the repo from the file system, specific error is: " . $_SESSION["error"], 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, contact admin if you can't see your repo anymore");
                }
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        function updateRepo(string $email, string $repoToEdit): bool {
            
            $currentDate = date("Y-m-d", time());
            
            try {
                $this->activateConn();

                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM repos WHERE (Owner = ? && Name = ?)", "ss", [$email, $repoToEdit]);

                if ($result->num_rows != 1) {
                    error_log("User $email tried to update a repo not of his own, or chosen user does not exist", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                $row = $result->fetch_assoc();

                $result = $this->dbQueryWithParams("UPDATE repos SET LastModified = ? WHERE (Owner = ? && Name = ?)", "sss", [$currentDate, $email, $repoToEdit]);

                if ($result != 1) {
                    error_log("Something went wrong while updating the repo in the database", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                // Following code deletes the old file and replaces it with the new one
                $repoPath = "../../repos/$email/$repoToEdit";

                $files = glob($repoPath . '/*'); // Gets all files and directories inside $repoPath and puts them in an array called $files

                if (count($files) > 0) {
                    $file = $files[0];
                    if (is_file($file) && is_writable($file)) 
                        unlink($file);
                    else {
                        error_log("Something went wrong while deleting the old file", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                        throw new Exception("Something went wrong, try again later");
                    }
                }

                $fileName = htmlspecialchars(trim($_FILES["fileUpload"]["name"]));
                $tempPath = $_FILES["fileUpload"]["tmp_name"]; // The server saves the file in a temporary location, so we need to move it to its final location

                if (!move_uploaded_file($tempPath, "../../repos/$email/$repoToEdit/$fileName")) {
                    error_log("Something went wrong while transferring the file into its new location", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            $this->closeConn();
            return true;
        }


        // Aux Methods //
        function deleteDirectory($dirPath): bool {
            try {
                if (!is_dir($dirPath)) 
                    throw new Exception("$dirPath must be a directory");

                // Following code checks if $dirPath ends with a /, if not it adds it (done to avoid errors)
                if (substr($dirPath, strlen($dirPath) - 1, 1) != '/')
                    $dirPath .= '/';

                // Following code gets all files and directories inside $dirPath and puts them in an array called $files
                $files = glob($dirPath . '*', GLOB_MARK);

                foreach ($files as $file) {
                    if (is_dir($file)) 
                        if (is_writable($file))
                            $this->deleteDirectory($file);
                        else
                            throw new Exception("Directory $file is not writable");
                    else 
                        if (is_writable($file))
                            unlink($file);
                        else
                            throw new Exception("File $file is not writable");
                    }

                if (is_writable($dirPath))
                    rmdir($dirPath);
                else 
                    throw new Exception("Directory $dirPath is not writable");
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            return true;
        }

        function emailExists($email): bool {
            $this->activateConn();
            $result = $this->dbQueryWithParams("SELECT email FROM users WHERE email = ?", "s", [$email]);
            return ($result->num_rows == 0) ? false : true;
        }

        // Used in editUser and updateProfile methods
        function checkCommonEditData ($email, $newEmail, $firstname, $lastname) {
            $email = trim($_POST["email"]);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                error_log("Email is not valid", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Email is not valid, please try again");
            }

            $hasEmailChanged = ($email != $newEmail);

            if ($hasEmailChanged) {
                if ($this->emailExists($newEmail)) {
                    error_log("Email already exists", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Email already exists, please try again");
                }

                if (strlen($newEmail) > 64) {
                    error_log("Email is too long", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Email is too long, please try again");
                }
            }

            if (strlen($firstname) > 64){
                error_log("Firstname is too long", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Firstname is too long, please try again");
            }

            if (strlen($lastname) > 64){
                error_log("Lastname is too long", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Lastname is too long, please try again");
            }
        }
    }
?>