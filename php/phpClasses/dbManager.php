<?php

    require_once("user.php");
    require_once("cookieManager.php");
    require_once("sessionManager.php");

    class dbManager {  
    
        private const DB_SERVER = "localhost";
        private const USERNAME = "S5311626";
        private const PASSWORD = "NothingHappenedInTiananmenSquareIn1989";
        private const DB_NAME = "S5311626";
        private const EMAIL_REGEX = "/^[_a-z0-9.-]+@[a-z0-9-]+(.[a-z]{2,3})$/";
        protected $conn; // this fields is protected because the class dbManagerAdmin extends this class and needs to access it

        function __destruct() { $this->closeConn(); }

        function activateConn() {
            try {
                if (!isset($this->conn))
                    if (!($this->conn = new mysqli(dbManager::DB_SERVER, dbManager::USERNAME, dbManager::PASSWORD, dbManager::DB_NAME))) {
                        error_log("[" . date("Y-m-d H:i:s") . "] Error: cannot connect to database", 3 , "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                        throw new Exception("Error: cannot connect to DB");
                    }
            }
            catch (Exception $e) { die ($e->getMessage() . mysqli_connect_error()); } // TODO if die then create error page rather than BOOM the server
        }

        function closeConn() { // this method closes the connection
            if ($this->conn) {
                $this->conn->close();
                $this->conn = null;
            }
        }


        // Query functions //
        /*
         * this method is the core of the class, it takes a query as a string and its parameters and executes it.
         * not only it executes it but it also checks if the query is correct and if the parameters are correct.
         * it returns the result of the query if it's a SELECT, otherwise it returns the number of affected rows
         */
        function dbQueryWithParams(string $query, string $paramsTypes, array $params) {
            try {
                if (!($stmt = $this->conn->prepare($query))) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Error: cannot prepare the following query -> " . $query. "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("server error");
                }
                if (count($params) != strlen($paramsTypes)) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Error: number of parameters does not match the number of types". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("server error");
                }
                if (!($stmt->bind_param($paramsTypes, ...$params))) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Error: cannot bind the following parameters -> " . $params. "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("server error");
                }
                if (!($stmt->execute())) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Error: cannot execute the following query -> " . $query. "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
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

        // used ONLY for queries without params (it's more efficient because it has no statements)
        function dbQueryWithoutParams(string $query) {
            try {
                if (($result = $this->conn->query($query)) == false) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Error: cannot execute the following query -> " . $query. "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Server error");
                }
            }
            catch (Exception $e) { die($e->getMessage() . $this->conn->error); }

            return $result;
        }


        // User functions //

        // this method is used to register a new user, returns a bool to check if the registration was successful or not
        function registerUser($user): bool {
            try {
                $this->activateConn();

                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$user->getEmail()]);

                if ($result->num_rows != 0) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Email already in use". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Email already in use");
                }
    
                $paramArr = [$user->getEmail(), $user->getPassword(), $user->getFirstName(), $user->getLastName()];
                $result = $this->dbQueryWithParams('INSERT INTO users (email, password, firstname, lastname, username, permission, pfp, gender, birthday, description, newsletter) VALUES (?, ?, ?, ?, null, "user", null, "notSpecified", null, null, false)', 'ssss', $paramArr);
    
                if ($result != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] cannot insert user into database (1 expected)". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Something went wrong, please try again later");
                }

                // Creates a new directory for user's repos 
                $email = $user->getEmail();
                mkdir("/chroot/home/S5311626/public_html/repos/$email");
                chmod("/chroot/home/S5311626/public_html/repos/$email", 0777);
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

        // this method is used to login a user, returns a bool to check if the login was successful or not
        function loginUser($user): bool {
            try {
                $this->activateConn();
                
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$user->getEmail()]);

                if ($result->num_rows != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Email not found". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Email not found, please try again");
                }
    
                $row = $result->fetch_assoc();
    
                if (!password_verify($user->getPassword(), $row["password"])) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Wrong password". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Wrong password. please try again");
                }

                if ($this->isBanned($user->getEmail())) {
                    error_log("[" . date("Y-m-d H:i:s") . "] User is banned". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("You are banned. Please contact an admin if you think you didn't violate our terms and conditions");
                }
    
                if ($user->getRemMeFlag()) { 
                    $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? AND (STR_TO_DATE(ExpDate, '%Y-%m-%d') < CURDATE()))", "s", [$user->getEmail()]);
    
                    $actTime = time();
                    $oneWeek = 604800; // 60 * 60 * 24 * 7 = 604800 seconds = 1 week
                    $expDate = date("Y-m-d", $actTime + $oneWeek);
                    $UID = hash("sha512", (bin2hex(random_bytes(32)) . $actTime));
                    
                    $paramArr = [$UID, $user->getEmail(), $expDate];
                    $result = $this->dbQueryWithParams("INSERT INTO remMeCookies (UID, email, ExpDate) VALUES (?, ?, ?)", "sss", $paramArr);
    
                    if ($result != 1) {
                        error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong in INSERT INTO (1 expected), try again later". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
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


        // Checks whether the user is banned or not, returns true on success, false otherwise
        function isBanned(string $email): bool {
            $result = $this->dbQueryWithParams("SELECT permission FROM users WHERE email = ?", "s", [$email]);
            $permission = $result->fetch_assoc()["permission"];
            return ($permission == "banned");
        }

        // Editing profile functions (users only)

        /*
         * this user takes the email of the user that wants to edit his profile and the sessionManager object
         * which contains all the user session's data, it returns a bool to check if the edit was successful or not
         */
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
                    error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong while updating user's data from Manage Users page". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                // If email has changed, we need to update some data related to repos and cookies
                if ($hasEmailChanged) {

                    $sessionManager->setEmail($newEmail);

                    // Updates all remember me cookies from current email to the new one, 
                    $result = $this->dbQueryWithParams("UPDATE remMeCookies SET email = ? WHERE email = ?", "ss", [$newEmail, $email]);
                    $result = $this->dbQueryWithParams("UPDATE repos SET Owner = ? WHERE Owner = ?", "ss", [$newEmail, $email]);
                    rename("/chroot/home/S5311626/public_html/repos/$email", "/chroot/home/S5311626/public_html/repos/$newEmail");
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

        /*
         * this method is used to update the user's password
         * it takes the user's email as a parameter and
         * returns a bool to check if the update was successful or not
         */
        function updatePassword(string $email): bool {

            $this->activateConn();

            $this->conn->begin_transaction();

            $oldPassword = trim($_POST["oldPassword"]);
            $newPassword = trim($_POST["newPassword"]);

            try {
                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$email]);

                if ($result->num_rows != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong while updating the pw. (result->num_rows expected to be 1)". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Something went wrong while updating the password, please try again");
                }

                $row = $result->fetch_assoc();

                if (!password_verify($oldPassword, $row["password"])) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Wrong password". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Wrong password, please try again");
                }

                if (strlen($newPassword) < 8) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Password must be at least 8 characters long". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Password must be at least 8 characters long, please try again");
                }

                $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                $result = $this->dbQueryWithParams("UPDATE users SET password = ? WHERE email = ?", "ss", [$newPassword, $email]);

                if ($result != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong while updating the pw. (result expected to be 1)". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
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
        
        /*
         * this method is used to delete the remember me cookie from the database
         * it takes the cookie and the user's email as parameters and
         * returns a bool to check if the deletion was successful or not
         */
        function deleteRememberMeCookieFromDB(string &$cookie, string $email): bool {    // Used for logout
            try {
                $this->activateConn();
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("DELETE FROM remMeCookies WHERE (email = ? AND UID = ?)", "ss", [$email, $cookie]);

                if ($result != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Couldn't delete the cookie from the database (0 found)". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
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

        /*
         * this method is used to recover the session from the remember me cookie
         * it takes the cookie and the user's session as parameters and
         * returns void
         */
        function recoverSession(string &$cookie, &$session): void {
            $this->activateConn();
            
            $cookieArr = explode(" ", $cookie);

            $result = $this->dbQueryWithParams("SELECT * FROM remMeCookies WHERE (UID = ? AND (STR_TO_DATE(ExpDate, '%Y-%m-%d') > CURDATE()))", "s", [$cookieArr[0]]);
            
            if ($result->num_rows == 1) { // if we find it then we check when its expiring date, if it's not valid we delete it
                $row = $result->fetch_assoc();

                $result = $this->dbQueryWithParams("SELECT email, permission FROM users WHERE email = ?", "s", [$row["email"]]);
                $row = $result->fetch_assoc();
                    
                $session->setSessionVariablesEmailAndPermission($row["email"], $row["permission"]);
            }
            
            $this->closeConn();
            // otherwise session won't be set
        }
            

        // Search Area tools //

        // takes a query as a parameter and returns an array of users that match the query
        function searchUsers(string &$userQuery): array {
            $this->activateConn();

            $userQuery = "%" . htmlspecialchars(trim($userQuery)) . "%";
            $result = $this->dbQueryWithParams("SELECT email, firstname, lastname FROM users WHERE (email LIKE ? OR firstname LIKE ? OR lastname LIKE ?)", "sss", [$userQuery, $userQuery, $userQuery]);
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();

            return $finalResult;      
        }

        // takes a query as a parameter and returns an array of repos that match the query
        function searchRepos(string &$repoQuery): array {
            $this->activateConn();
            
            $repoQuery = "%" . htmlspecialchars(trim($repoQuery)) . "%";
            $result = $this->dbQueryWithParams("SELECT Name, Owner, CreationDate, LastModified FROM repos WHERE (Owner LIKE ? OR Name LIKE ?)", "ss", [$repoQuery, $repoQuery]);
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();

            return $finalResult;
        }

        // takes the user's email as a parameter and returns an array of repos which belong to that user
        function showRepos(string $email): array {
            $this->activateConn();
            
            $result = $this->dbQueryWithParams("SELECT Name, CreationDate, LastModified FROM repos WHERE Owner = ?", "s", [$email]);
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();

            return $finalResult;
        }


        // DB Repos Manipulation //

        /*
         * this method is used to add a new repo to the database.
         * it takes the user's email as a parameter and
         * returns a bool to check if the addition was successful or not.
         * it creates a new directory for the repo only if everything goes well 
         */
        function addNewRepo(string $email): bool {
            $reposName = htmlspecialchars(trim($_POST["reposName"]));
            $fileName = htmlspecialchars(trim($_FILES["fileUpload"]["name"]));
            $pathLocation = "/chroot/home/S5311626/public_html/repos/$email/$reposName";
            $currentDate = date("Y-m-d", time());
        
            try {
                $this->activateConn();
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("INSERT INTO repos (Name, Owner, CreationDate, LastModified, RepoLocation) VALUES (?, ?, ?, ?, ?)", "sssss", [$reposName, $email, $currentDate, $currentDate, $pathLocation]);

                if ($result != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Repos could not be created in database". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                if (!mkdir($pathLocation)) {
                    $error = error_get_last();
                    error_log($error["message"] . " Current value in pathLocation is: " . $pathLocation. "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }
        
                chmod($pathLocation, 0766);
            
                $tempPath = $_FILES["fileUpload"]["tmp_name"];
        
                if (!move_uploaded_file($tempPath, $pathLocation . "/$fileName")) {
                    error_log("[" . date("Y-m-d H:/chroot/home/S5311626/public_html/repos/i:s") . "] Something went wrong while transferring the file into its new location". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
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

        /*
         * this method is used to delete a repo from the database.
         * it takes the user's email and the repo's name as parameters and
         * returns a bool to check if the deletion was successful or not.
         * it deletes the directory only if everything goes well
         */
        function deleteRepo(string $email, string $repoName): bool {
            
            try{
                $this->activateConn();
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM repos WHERE (Owner = ? AND Name = ?)", "ss", [$email, $repoName]);
                
                if ($result->num_rows != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] User $email tried to delete a repo not of his own, or chosen user does not exist". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                $result = $this->dbQueryWithParams("DELETE FROM repos WHERE (Owner = ? AND Name = ?)", "ss", [$email, $repoName]);

                if ($result != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong while deleting the repo from the database". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                if (!$this->deleteDirectory("../../repos/$email/$repoName")) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong when trying to delete the repo from the file system, specific error is: " . $_SESSION["error"]. "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
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

                $result = $this->dbQueryWithParams("SELECT * FROM repos WHERE (Owner = ? AND Name = ?)", "ss", [$email, $repoToEdit]);

                if ($result->num_rows != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] User $email tried to update a repo not of his own, or chosen user does not exist". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                $row = $result->fetch_assoc();

                $result = $this->dbQueryWithParams("UPDATE repos SET LastModified = ? WHERE (Owner = ? AND Name = ?)", "sss", [$currentDate, $email, $repoToEdit]);

                if ($result != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong while updating the repo in the database". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                // Following code deletes the old file and replaces it with the new one
                $repoPath = "/chroot/home/S5311626/public_html/repos/$email/$repoToEdit";

                $files = glob($repoPath . '/*'); // Gets all files and directories inside $repoPath and puts them in an array called $files

                if (count($files) > 0) {
                    $file = $files[0];
                    if (is_file($file) && is_writable($file)) 
                        unlink($file);
                    else {
                        error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong while deleting the old file". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                        throw new Exception("Something went wrong, try again later");
                    }
                }

                $fileName = htmlspecialchars(trim($_FILES["fileUpload"]["name"]));
                $tempPath = $_FILES["fileUpload"]["tmp_name"]; // The server saves the file in a temporary location, so we need to move it to its final location

                if (!move_uploaded_file($tempPath, "/chroot/home/S5311626/public_html/repos/$email/$repoToEdit/$fileName")) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong while transferring the file into its new location". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
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

                // checks if $dirPath ends with a /, if not it adds it (done to avoid errors)
                if (substr($dirPath, strlen($dirPath) - 1, 1) != '/')
                    $dirPath .= '/';

                // gets all files and directories inside $dirPath and puts them in an array called $files
                $files = glob($dirPath . '*', GLOB_MARK);

                foreach ($files as $file) {
                    if (is_dir($file)) {
                        if (is_writable($file))
                            $this->deleteDirectory($file);
                        else
                            throw new Exception("Directory $file is not writable");
                    }
                    else {
                        if (is_writable($file))
                            unlink($file);
                        else
                            throw new Exception("File $file is not writable");
                    }
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

        // returns true if the email exists in the database, false otherwise
        function emailExists($email): bool {
            $this->activateConn();
            $result = $this->dbQueryWithParams("SELECT email FROM users WHERE email = ?", "s", [$email]);
            return ($result->num_rows == 0) ? false : true;
        }

        // used in editUser and updateProfile methods
        function checkCommonEditData(string $email, string $newEmail, string $firstname, string $lastname): void {
            $email = trim($_POST["email"]);
            if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                error_log("[" . date("Y-m-d H:i:s") . "] Email is not valid". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("Email is not valid, please try again");
            }

            $hasEmailChanged = ($email != $newEmail);

            if ($hasEmailChanged) {
                if ($this->emailExists($newEmail)) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Email already exists". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Email already exists, please try again");
                }

                if (strlen($newEmail) > 64) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Email is too long". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                    throw new Exception("Email is too long, please try again");
                }
            }

            if (strlen($firstname) > 64) {
                error_log("[" . date("Y-m-d H:i:s") . "] Firstname is too long". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("Firstname is too long, please try again");
            }

            if (strlen($lastname) > 64) {
                error_log("[" . date("Y-m-d H:i:s") . "] Lastname is too long". "\n", 3, "/chroot/home/S5311626/public_html/texts/errorLog.txt");
                throw new Exception("Lastname is too long, please try again");
            }
        }
    }
?>