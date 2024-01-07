<?php

    class dbManagerAdmin extends dbManager {

        // Admin Tools //

        function manageUsers() {
            
            $result = $this->dbQueryWithoutParams("SELECT * FROM users");

            echo "
                <table id='table-manageUsers'>
                <thead>
                    <tr><th>Firstname</th><th>Lastname</th><th>Email</th><th>Permission</th><th>Delete</th><th>Edit</th></tr>
                </thead>
                <tbody>
            ";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                
                echo "<td>" . htmlspecialchars($row["firstname"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["lastname"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["permission"]) . "</td>";
                echo "<td><a href='./adminScripts/deleteUser.php?email=" . urlencode(htmlspecialchars($row["email"])) . "' onclick='return confirmDelete();'><i class='fa-solid fa-trash'></i></a></td>";
                echo "<td><a href='./editUserForm.php?email=" . urlencode(htmlspecialchars($row["email"])) . "'><i class='fa-solid fa-pencil'></i></a></td>";
                echo "<td>" . htmlspecialchars($row["permission"]) . "</td>";


                echo "</tr>";
            }

            echo "</tbody>
                </table>
            ";
        }

        function manageSubbedToNewsletter() {
            $result = $this->dbQueryWithoutParams("SELECT * FROM users WHERE newsletter = 1");
           
            echo "
                <table id='table-manageNewsletter'>
                <thead>
                    <tr><th>Firstname</th><th>Lastname</th><th>Email</th><th>Send Email</th></tr>
                </thead>
                <tbody>
            ";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";

                echo "<td>" . htmlspecialchars($row["firstname"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["lastname"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td><input type='checkbox' name='userCheckbox[]' value='" . htmlspecialchars($row["email"]) . "'></td>";

                echo "</tr>";
            }

            echo "
                </tbody>
                </table>
            ";
        }

        function deleteUser($userEmail) {
            
            $this->conn->begin_transaction();

            try {
                $result = $this->dbQueryWithParams("DELETE FROM users WHERE email=?", "s", [$userEmail]);

                if ($result != 1) {
                    error_log("Something went wrong when deleting a user, probably user wasn't defined, see final error: " . error_get_last(), 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong when trying to delete user, see log file to know more");
                }
            } 
            catch (Exception $e) {
                $this->conn->rollback();
                $_SESSION["error"] = $e->getMessage();
                return false;
            }
            
            $this->conn->commit();
            return true;
        }

        function editUser($userEmail) {

            try {
                $this->conn->begin_transaction();

                $result = $this->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$userEmail]); 
                if($result->num_rows != 1) {
                    error_log("This user does not exist", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Tried to edit a user that does not exist");
                }

                // Sets data names and data 
                $dataTypeToUpdate = "";
                $dataToUpdate = array(); 
                $isEmailModified = !empty($_POST["email"]);
                $isPasswordModified = !empty($_POST["pass"]);
                $isPermissionModified = !empty($_POST["permission"]);

                foreach ($_POST as $dataName => $data) {
                    if (!empty($data)) {
                        $dataTypeToUpdate .= " " . $dataName . " = ?,";
                        array_push($dataToUpdate, trim(htmlspecialchars($data)));
                    }
                }


                if ($isPasswordModified) {
                    $pass = htmlspecialchars(trim($_POST["pass"]));

                    if (strlen($pass) < 8) {
                        error_log("Choose a password with at least 8 characters", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                        throw new Exception("Choose a password with at least 8 characters");
                    }

                    $newPass = password_hash($pass, PASSWORD_DEFAULT);

                    substr_replace($pass, $newPass, 0);
                }


                if ($isEmailModified) { // Following code checks if email has changed, if so, it checks if email is valid, if so it changes session data and everything related to that email 
                    $newEmail = htmlspecialchars(trim($_POST["email"]));
                    
                    $result = $this->dbQueryWithParams("SELECT email FROM users WHERE email = ?", "s", [$newEmail]);
                    
                    if ($result->num_rows == 1) {
                        error_log("Email already exists", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                        throw new Exception("Email already exists, please try again");
                    }

                    // Updates all remember me cookies from current email to the new one, 
                    // TODO ask if should delete them instead of updating them
                    $result = $this->dbQueryWithParams("UPDATE remMeCookies SET email = ? WHERE email = ?", "ss", [$newEmail, $userEmail]);
                    $result = $this->dbQueryWithParams("UPDATE repos SET Owner = ? WHERE Owner = ?", "ss", [$newEmail, $userEmail]);
                    rename("../../repos/$userEmail", "../../repos/$newEmail");
                }


                if ($isPermissionModified) {
                    $newPermission = htmlspecialchars(trim($_POST["permission"]));

                    
                    if ($newPermission != "user" && $newPermission != "mod" && $newPermission != "admin" && $newPermission != "banned") {
                        error_log("Invalid permission", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                        throw new Exception("Invalid permission");
                    }

                    $result = $this->dbQueryWithParams("UPDATE users SET permission = ? WHERE email = ?", "ss", [$newPermission, $userEmail]);
                }

                // cleans data to be used in query function
                $dataTypeToUpdate = str_replace(", userEmail = ?, submit = ?,", "", $dataTypeToUpdate);
                array_pop($dataToUpdate);
                array_pop($dataToUpdate);
                array_push($dataToUpdate, $userEmail); // Adds last value to be used in query function

                // Sets data types for query function            
                $dataCount = "";
                for ($i = count($dataToUpdate); $i > 0; $i--) 
                    $dataCount .= "s";

                $result = $this->dbQueryWithParams("UPDATE users SET " . $dataTypeToUpdate . " WHERE email = ?", $dataCount, $dataToUpdate);
                if ($result != 1){
                    error_log("Something went wrong while updating user's data from Manage Users page", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong, try again later");
                }
            
            }
            catch (Exception $e) {
                $this->conn->rollback();
                $_SESSION["error"] = $e->getMessage();
                return false;
            }

            $this->conn->commit();
            return true;
        }



        // TODO Da sistemare

        function banUser($userEmail) {
            $result = $this->dbQueryWithParams("UPDATE users SET permission = 'banned' WHERE email = ?", "s", [$userEmail]);
            $stmt = $this->conn->prepare($result);
            $stmt->bind_param("s", $userEmail);
            $stmt->execute();   
        }

    }

?>