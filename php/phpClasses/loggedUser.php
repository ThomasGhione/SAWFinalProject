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

        function __construct(string $email) {
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
        function setFirstname(string $firstname): void { $this->firstname = $firstname; }
        function setLastname(string $lastname): void { $this->lastname = $lastname; }
        function setEmail(string $email): void { $this->email = $email; }
        function setUsername(string $username): void { $this->username = $username; }
        function setPfp($pfp): void { $this->pfp = $pfp; }
        function setGender($gender): void { $this->gender = $gender; }
        function setBirthday($birthday): void { $this->birthday = $birthday; }
        function setDescription(string $description): void { $this->description = $description; }
        function setNewsletter($newsletter): void { $this->newsletter = $newsletter; }
    
        // Getter methods
        function getFirstname(): string { return $this->firstname; }
        function getLastname(): string { return $this->lastname; }
        function getEmail(): string { return $this->email; }
        function getUsername(): string { return $this->username; }
        function getPfp() { return $this->pfp; }
        function getGender() { return $this->gender; }
        function getBirthday() { return $this->birthday; }
        function getDescription(): string { return $this->description; }
        function getNewsletter() { return $this->newsletter; }

    }
?>