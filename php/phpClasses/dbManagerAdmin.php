<?php

    require_once("dbManager.php");

    class dbManagerAdmin extends dbManager {

        // Admin Tools //

        // this method returns the array of all the users in the database
        function manageUsers(): array {
            $this->activateConn();
            
            $result = $this->dbQueryWithoutParams("SELECT * FROM users");
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();
            return $finalResult;
        }

        // this method returns the array of all the users in the database that are subscribed to the newsletter
        function manageSubbedToNewsletter(): array {
            $this->activateConn();
            
            $result = $this->dbQueryWithoutParams("SELECT * FROM users WHERE newsletter = 1");
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();
            return $finalResult;
        }

        // this method returns a bool that indicates if the user's been unbanned successfully
        function unbanUser(string $userEmail): bool {
            $this->activateConn();
            $this->conn->begin_transaction();

            try {
                $result = $this->dbQueryWithParams("UPDATE users SET permission = 'user' WHERE email = ?", "s", [$userEmail]);
                if ($result != 1)
                    throw new Exception("Something went wrong when unbanning a user, probably user wasn't defined, see final error: " . error_get_last());
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                error_log($e->getMessage(). "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        // this method returns a bool that indicates if the user's been banned successfully
        function banUser(string $userEmail): bool {
            $this->activateConn();
            $this->conn->begin_transaction();

            try {
                $result = $this->dbQueryWithParams("UPDATE users SET permission = 'banned' WHERE email = ?", "s", [$userEmail]);
                if ($result != 1) // result should be 1, if it's not it means that the user wasn't found
                    throw new Exception("Something went wrong when banning a user, probably user wasn't defined, see final error: " . error_get_last());
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                error_log($e->getMessage(). "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        // this method returns a bool that indicates if the user's been deleted successfully
        function deleteUser(string $userEmail): bool {
            $this->activateConn();
            $this->conn->begin_transaction();

            try {
                $result = $this->dbQueryWithParams("DELETE FROM users WHERE email=?", "s", [$userEmail]);
                if ($result != 1) // result should be 1, if it's not it means that the user wasn't found
                    throw new Exception("Something went wrong when deleting a user, probably user wasn't defined, see final error: " . error_get_last());
            } 
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                error_log($e->getMessage(). "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                $_SESSION["error"] = $e->getMessage();
                return false;
            }
            
            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        // this method returns a bool that indicates if the user's been edited successfully
        function editUser(string $userEmail, &$sessionManager): bool {
            try {
                $this->activateConn();
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$userEmail]); 
                if ($result->num_rows != 1) { // if the user doesn't exist throws an error
                    error_log("[" . date("Y-m-d H:i:s") . "] This user does not exist". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Tried to edit a user that does not exist");
                }

                $newEmail = trim($_POST["email"]);
                $firstname = htmlspecialchars(trim($_POST["firstname"]));
                $lastname = htmlspecialchars(trim($_POST["lastname"]));

                $this->checkCommonEditData($userEmail, $newEmail, $firstname, $lastname);

                $hasEmailChanged = ($userEmail != $newEmail);


                $permission = $_POST["permission"];

                // permission is valid only if it's one of the following: user, mod, admin, banned
                if ($permission != "user" && $permission != "mod" && $permission != "admin" && $permission != "banned") {
                    error_log("[" . date("Y-m-d H:i:s") . "] Invalid permission". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Invalid permission");
                }


                $result = $this->dbQueryWithParams("UPDATE users SET firstname = ?, lastname = ?, email = ?, permission = ? WHERE email = ?", "sssss", [$firstname, $lastname, $newEmail, $permission, $userEmail]);

                if ($result != 1){
                    error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong while updating user's data from Manage Users page". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }

                if ($hasEmailChanged) { // Following code checks if email has changed, if so it changes session data and everything related to that email in the database (including the folder name)

                    $result = $this->dbQueryWithParams("UPDATE remMeCookies SET email = ? WHERE email = ?", "ss", [$newEmail, $userEmail]);
                    $result = $this->dbQueryWithParams("UPDATE repos SET Owner = ? WHERE Owner = ?", "ss", [$newEmail, $userEmail]);
                    rename("../../repos/$userEmail", "../../repos/$newEmail");
                }

                if ($sessionManager->getEmail() === $newEmail)
                    $sessionManager->setSessionVariablesEmailAndPermission($newEmail, $permission);
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

        function editUserPass(string $userEmail): bool {
            try {
                $this->activateConn();
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$userEmail]); 
                if ($result->num_rows != 1) { // user must exists
                    error_log("[" . date("Y-m-d H:i:s") . "] This user does not exist". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Tried to edit a user that does not exist");
                }

                $pass = trim($_POST["pass"]);
                if (strlen($pass) < 8) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Choose a password with at least 8 characters". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Choose a password with at least 8 characters");
                }
                $pass = password_hash($pass, PASSWORD_DEFAULT);

                $result = $this->dbQueryWithParams("UPDATE users SET password = ? WHERE email = ?", "ss", [$pass, $userEmail]);

                if ($result != 1) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Something went wrong while updating user's data from Manage Users page". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
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
    }
?>