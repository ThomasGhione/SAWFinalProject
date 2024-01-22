<?php

    require_once("dbManager.php");

    class dbManagerAdmin extends dbManager {

        // Admin Tools //

        function manageUsers(): array {
            $this->activateConn();
            
            $result = $this->dbQueryWithoutParams("SELECT * FROM users");
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();
            return $finalResult;
        }

        function manageSubbedToNewsletter(): array {
            $this->activateConn();
            
            $result = $this->dbQueryWithoutParams("SELECT * FROM users WHERE newsletter = 1");
            $finalResult = $result->fetch_all(MYSQLI_ASSOC);

            $this->closeConn();
            return $finalResult;
        }

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
                error_log($e->getMessage(), 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        function banUser(string $userEmail): bool {
            $this->activateConn();
            $this->conn->begin_transaction();

            try {
                $result = $this->dbQueryWithParams("UPDATE users SET permission = 'banned' WHERE email = ?", "s", [$userEmail]);
                if ($result != 1)
                    throw new Exception("Something went wrong when banning a user, probably user wasn't defined, see final error: " . error_get_last());
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                error_log($e->getMessage(), 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        function deleteUser(string $userEmail): bool {
            $this->activateConn();
            $this->conn->begin_transaction();

            try {
                $result = $this->dbQueryWithParams("DELETE FROM users WHERE email=?", "s", [$userEmail]);
                if ($result != 1)
                    throw new Exception("Something went wrong when deleting a user, probably user wasn't defined, see final error: " . error_get_last());
            } 
            catch (Exception $e) {
                $this->conn->rollback();
                $this->closeConn();
                error_log($e->getMessage(), 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                $_SESSION["error"] = $e->getMessage();
                return false;
            }
            
            $this->conn->commit();
            $this->closeConn();
            return true;
        }

        function editUser(string $userEmail, &$sessionManager): bool {
            try {
                $this->activateConn();
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$userEmail]); 
                if ($result->num_rows != 1) {
                    error_log("This user does not exist", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Tried to edit a user that does not exist");
                }

                $newEmail = trim($_POST["email"]);
                $firstname = htmlspecialchars(trim($_POST["firstname"]));
                $lastname = htmlspecialchars(trim($_POST["lastname"]));

                $this->checkCommonEditData($userEmail, $newEmail, $firstname, $lastname);

                $hasEmailChanged = ($userEmail != $newEmail);


                $permission = $_POST["permission"];

                if ($permission != "user" && $permission != "mod" && $permission != "admin" && $permission != "banned") {
                    error_log("Invalid permission", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Invalid permission");
                }

                $pass = trim($_POST["pass"]);
                if (strlen($pass) < 8) {
                    error_log("Choose a password with at least 8 characters", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Choose a password with at least 8 characters");
                }
                $pass = password_hash($pass, PASSWORD_DEFAULT);

                $result = $this->dbQueryWithParams("UPDATE users SET firstname = ?, lastname = ?, email = ?, permission = ?, password = ? WHERE email = ?", "ssssss", [$firstname, $lastname, $newEmail, $permission, $pass, $userEmail]);

                if ($result != 1){
                    error_log("Something went wrong while updating user's data from Manage Users page", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
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
    }
?>