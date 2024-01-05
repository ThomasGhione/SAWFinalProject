<?php

    class loggedUser {

        private $firstname;
        private $lastname;
        private $email;
        private $username;
        private $pfp;
        private $gender;
        private $birthday;
        private $description;
        private $newsletter;

        function __construct ($email) {
            $dbManager = new dbManager();
            $result = $dbManager->dbQueryWithParams("SELECT * FROM users WHERE email = ?", "s", [$email]);
            try {
                if ($result->num_rows == 0) {
                    error_log("Query failed", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Something went wrong. Please try again later");
                }
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                header("Location: ../loginForm.php");
                exit;
            }

            $row = $result->fetch_assoc();

            $this->setFirstname(htmlspecialchars($row["firstname"]));
            $this->setLastname(htmlspecialchars($row["lastname"]));
            $this->setEmail(htmlspecialchars($row["email"]));
            
            if ($row["username"] != NULL)
                $this->setUsername(htmlspecialchars($row["username"]));

            if ($row["pfp"] != NULL)
                $this->setPfp(htmlspecialchars($row["pfp"]));

            $this->setGender(htmlspecialchars($row["gender"]));
            
            if ($row["birthday"] != NULL)
                $this->setBirthday(htmlspecialchars($row["birthday"]));

            if ($row["description"] != NULL)
                $this->setDescription(htmlspecialchars($row["description"]));

            $this->setNewsletter(htmlspecialchars($row["newsletter"]));
        }


        // Setter methods
        function setFirstname($firstname) { $this->firstname = $firstname; }
        function setLastname($lastname) { $this->lastname = $lastname; }
        function setEmail($email) { $this->email = $email; }
        function setUsername($username) { $this->username = $username; }
        function setPfp($pfp) { $this->pfp = $pfp; }
        function setGender($gender) { $this->gender = $gender; }
        function setBirthday($birthday) { $this->birthday = $birthday; }
        function setDescription($description) { $this->description = $description; }
        function setNewsletter($newsletter) { $this->newsletter = $newsletter; }
    
        // Getter methods
        function getFirstname() { return $this->firstname; }
        function getLastname() { return $this->lastname; }
        function getEmail() { return $this->email; }
        function getUsername() { return $this->username; }
        function getPfp() { return $this->pfp; }
        function getGender() { return $this->gender; }
        function getBirthday() { return $this->birthday; }
        function getDescription() { return $this->description; }
        function getNewsletter() { return $this->newsletter; }

    }
?>