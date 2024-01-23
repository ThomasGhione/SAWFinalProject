<?php

    class User {
        private $firstname;
        private $lastname;
        private $email;
        private $password;
        private $permission;
        private $remMeFlag;
        private $newsletter;
        
        // since php doesn't support multiple constructors, we're passing the flag "$login" to distinguish between login and registration
        function __construct (bool $login) {            
            // before using the flag we need to check if the request is valid
            try {
                if (!isset($_POST["submit"]) || !isset($_POST["email"]) || !isset($_POST["pass"])) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Invalid request". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Invalid request");
                }

                if (empty($_POST["email"]) || empty($_POST["pass"])) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Empty parameters have been passed to the form". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Empty parameters have been passed to the form. Please try again");
                }
                
                $email = filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL);
                if ($email === false || strlen($email) > 64) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Invalid email". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Invalid email. Please try again");
                }

                $password = htmlspecialchars(trim($_POST["pass"]));
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                header("Location: ../loginForm.php");
                exit;
            }

            if ($login) { // calling the constructor extension for login if the flag is true
                $remMeFlag = isset($_POST["rememberMe"]);
                $this->cLogin($email, $password, $remMeFlag);           
            }
            else 
                $this->cRegister($email, $password);
            
        }

        function __destruct() {
            $this->firstname = null;
            $this->lastname = null;
            $this->email = null;
            $this->password = null;
        }

        // Constructor extensions

        // cLogin is only called in the constructor, it's used to set the variables for the login
        private function cLogin(string $email, string &$password, &$remMeFlag): void {
            $this->remMeFlag = $remMeFlag;
            $this->email = $email;
            $this->password = $password;
        }

        // cRegister is only called in the constructor, it's used to set the variables for the registration and check whether the request is valid
        private function cRegister(string $email, string &$password): void {
            try {
                if (!isset($_POST["firstname"]) || !isset($_POST["lastname"]) || !isset($_POST["confirm"])) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Invalid request". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Invalid request");
                }

                if (empty($_POST["firstname"]) || empty($_POST["lastname"]) || empty($_POST["confirm"])) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Empty parameters have been passed to the form". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Empty parameters have been passed to the form. Please try again later");
                }

                $firstname = htmlspecialchars(trim($_POST["firstname"]));
                $lastname = htmlspecialchars(trim($_POST["lastname"]));
                
                if (strlen($firstname) > 64) {
                    error_log("[" . date("Y-m-d H:i:s") . "] First name is too long". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("First name is too long. Please try again");
                }       
                if (strlen($lastname) > 64) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Last name is too long". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Last name is too long. Please try again");
                }
                
                $confirm = htmlspecialchars(trim($_POST["confirm"]));
                
                if ($this->isPasswordWeak($password)) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Password isn't strong enough". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Password isn't strong enough. Please choose a stronger password");
                }
                
                if (!$this->passwordMatches($password, $confirm)) {
                    error_log("[" . date("Y-m-d H:i:s") . "] Passwords don't match". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Passwords don't match");
                }
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                header("Location: ../registrationForm.php");
                exit;
            }

            // after checking for errors, we can set the variables
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->email = $email;
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }

        // Getters
        function getFirstName(): string { return $this->firstname; }
        function getLastName(): string { return $this->lastname; }
        function getEmail(): string { return $this->email; }
        function getPassword(): string { return $this->password; }
        function getPermission() { return $this->permission; }
        function getRemMeFlag() { return $this->remMeFlag; }
        function getNewsletter() { return $this->newsletter; }
        function getUser(): array {
            return array(
                "firstName" => $this->getFirstName(),
                "lastName" => $this->getLastName(),
                "email" => $this->getEmail(),
                "password" => $this->getPassword(),
                "permission" => $this->getPermission(),
                "remMeFlag" => $this->getRemMeFlag(),
                "newsletter" => $this->getNewsletter()
            );
        }

        // Setters
        function setFirstName(string &$firstname): void { $this->firstname = htmlspecialchars($firstname); }
        function setLastName(string &$lastname): void { $this->lastname = htmlspecialchars($lastname); }
        function setEmail(string $email): void { $this->email = filter_var($email, FILTER_VALIDATE_EMAIL); }
        function setPassword(string &$password): void { $this->password = htmlspecialchars($password); }
        function setConfirmPwd(string &$confirm): void { $this->password = htmlspecialchars($confirm); }
        function setPermission(&$permission): void { $this->permission = htmlspecialchars($permission); }
        function setRemMeFlag(&$remMeFlag): void { $this->remMeFlag = htmlspecialchars($remMeFlag); }
        function setNewsletter(&$newsletter): void {$this->newsletter = htmlspecialchars($newsletter); }
        
        
        // Aux methods
    
        private function isPasswordWeak(string &$password): bool {
            return strlen($password) < 8;
        }
    
        private function passwordMatches(string &$password, string &$confirmPwd): bool {
            return $password == $confirmPwd;
        }
        /*
        private function isPasswordWeak(string &$password): bool {
            if (strlen($password) < 8) {
                error_log("[" . date("Y-m-d H:i:s") . "] Pw is too short". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Password is too short, please choose a longer password");
            }
            if (strlen($password) > 24) {
                error_log("[" . date("Y-m-d H:i:s") . "] Pw is too long". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Password is too long, please choose a shorter password");
            }
            if (!preg_match('@[A-Z]@', $password)) {
                error_log("[" . date("Y-m-d H:i:s") . "] Pw doesn't contain uppercase letters". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Password doesn't contain uppercase letters, please try again");
            }
            if (!preg_match('@[a-z]@', $password)) {
                error_log("[" . date("Y-m-d H:i:s") . "] Pw doesn't contain lowercase letters". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Password doesn't contain lowercase letters, please try again");
            }
            if (!preg_match('@[0-9]@', $password)) {
                error_log("[" . date("Y-m-d H:i:s") . "] Pw doesn't contain numbers". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Password doesn't contain numbers, please try again");
            }
            if (!preg_match('@[^\w]@', $password)) {
                error_log("[" . date("Y-m-d H:i:s") . "] Pw doesn't contain special characters". "\n", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                throw new Exception("Password doesn't contain special characters, please try again");
            }

            return true;
        }
        */
    }

?>