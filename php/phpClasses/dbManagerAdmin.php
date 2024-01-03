<?php

    class dbManagerAdmin extends dbManager {
    
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

    }

?>