<?php

    class User {
        private $firstname;
        private $lastname;
        private $username;
        private $email;
        private $password;
        private $permission;
        private $remMeFlag;
        private $newsletter;
        
        private $emailregex = "/\S+@\S+\.\S+/";  // TODO correggere regex per email

        /*function __construct ($login, $email, $password,
                             $firstName = null, $lastName = null, $userName = null,
                             $confirmPwd = null, $gender = null, $birthday = null) {*/

        function __construct ($login) {            
            $email = htmlspecialchars(trim($_POST["email"]));
            $password = htmlspecialchars(trim($_POST["pass"]));

            try {                
                if (empty($email) || empty($password)) {
                    error_log("Empty parameters have been passed to the form", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Empty parameters have been passed to the form. Please try again");
                }
                if (!$this->isEmailValid()) {
                    error_log("Invalid email", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Invalid email. Please try again");
                }
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                header("Location: ../loginForm.php");
                exit;
            }

            if ($login) {
                $remMeFlag = isset($_POST["rememberMe"]);
                $this->cLogin($email, $password, $remMeFlag);
            }
            else {
                $firstname = htmlspecialchars($_POST["firstname"]);
                $lastname = htmlspecialchars($_POST["lastname"]);
                $confirm = htmlspecialchars($_POST["confirm"]);
                $this->cRegister($firstname, $lastname, $email, $password, $confirm);
            }
        }

        function __destruct() {
            $this->firstname = null;
            $this->lastname = null;
            $this->username = null;
            $this->email = null;
            $this->password = null;
        }

        // Constructor extensions

        private function cLogin(string $email, string &$password, &$remMeFlag): void {
            $this->remMeFlag = $remMeFlag;
            $this->email = trim($email);
            $this->password = trim($password);
        }

        private function cRegister(string &$firstname, string &$lastname, string $email, string &$password, string &$confirm): void {
            try {
                if (empty($firstname) || empty($lastname) || empty($confirm)) {
                    error_log("Empty parameters have been passed to the form", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Empty parameters have been passed to the form. Please try again later");
                }
                if ($this->isPasswordWeak($password)) {
                    error_log("Password isn't strong enough", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Password isn't strong enough (it needs to be at least 8 characters long), please choose a stronger password");
                }
                if (!$this->isPasswordValid($password, $confirm)) {
                    error_log("Passwords don't match", 3, $_SERVER["DOCUMENT_ROOT"] . "/SAW/SAWFinalProject/texts/errorLog.txt");
                    throw new Exception("Passwords don't match");
                }
            }
            catch (Exception $e) {
                $_SESSION["error"] = $e->getMessage();
                header("Location: ../registrationForm.php");
                exit;
            }

            $this->firstname = trim($firstname);
            $this->lastname = trim($lastname);
            $this->email = trim($email);
            $this->password = password_hash(trim($password), PASSWORD_DEFAULT);
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
        function setFirstName(string &$firstname): void { $this->firstname = $firstname; }
        function setLastName(string &$lastname): void { $this->lastname = $lastname; }
        function setEmail(string $email): void { $this->email = $email; }
        function setPassword(string &$password): void { $this->password = $password; }
        function setConfirmPwd(string &$confirm): void { $this->password = $confirm; }
        function setPermission(&$permission): void { $this->permission = $permission; }
        function setRemMeFlag(&$remMeFlag): void { $this->remMeFlag = $remMeFlag; }
        function setNewsletter(&$newsletter): void {$this->newsletter = $newsletter; }
        
        
        
        // Aux methods
    
        function getFullName(&$user): string {
            return $user->getFirstName() . " " . $user->getLastName();
        }
    
        function isPasswordWeak(string &$password): bool {
            return strlen($password) < 8;
        }
    
        function isPasswordValid(string &$password, string &$confirmPwd): bool {
            return $password == $confirmPwd;
        }
        
        function isEmailValid() {
            return !preg_match($this->emailregex, $this->email);
        }
    }

?>