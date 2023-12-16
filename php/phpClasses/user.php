<?php

    class User {
        private $firstname;
        private $lastname;
        private $username;
        private $email;
        private $password;
        private $permission;
        private $remMeFlag;
        
        private $emailregex = "/\S+@\S+\.\S+/";  // TODO correggere regex per email

        /*function __construct ($login, $email, $password,
                             $firstName = null, $lastName = null, $userName = null,
                             $confirmPwd = null, $gender = null, $birthday = null) {*/

        function __construct ($login) {
            
            $email = $_POST["email"];
            $password = $_POST["pass"];

            if (empty($email) || empty($password)) {
                error_log('Error: empty parameters');
                throw new Exception('Empty parameters passed to the form');
            }

            if (!$this->isEmailValid()) {
                error_log('Error: email is not valid');
                throw new Exception('Email is not valid');
            }

            if ($login) {
                $remMeFlag = isset($_POST["rememberMe"]);
                $this->cLogin($email, $password, $remMeFlag);
            }
            else{
                $firstname = $_POST["firstname"];
                $lastname = $_POST["lastname"];
                $confirm = $_POST["confirm"];
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

        public function cLogin($email, $password, $remMeFlag) {
            $this->remMeFlag = $remMeFlag;
            $this->email = trim($email);
            $this->password = trim($password);
        }

        public function cRegister($firstname, $lastname, $email, $password, $confirm) {

            if (empty($firstname) || empty($lastname) || empty($confirm)) {
                error_log('Error: empty parameters');
                throw new Exception('Empty parameters passed to the form');
            }

            if ($this->isPasswordWeak($password)) {
                error_log('Error: password is not strong enough');
                throw new Exception('Password is not strong enough');
            }

            if (!$this->isPasswordValid($password, $confirm)) {
                error_log('Error: passwords do not match');
                throw new Exception('Passwords do not match');
            }

            $this->firstname = trim($firstname);
            $this->lastname = trim($lastname);
            $this->email = trim($email);
            $this->password = password_hash(trim($password), PASSWORD_DEFAULT);
        }

        // Getters
        function getFirstName() { return $this->firstname; }
        function getLastName() { return $this->lastname; }
        function getEmail() { return $this->email; }
        function getPassword() { return $this->password; }
        function getPermission() { return $this->permission; }
        function getRemMeFlag() { return $this->remMeFlag; }

        function getUser() {
            return array(
                'firstName' => $this->getFirstName(),
                'lastName' => $this->getLastName(),
                'email' => $this->getEmail(),
                'password' => $this->getPassword(),
                'permission' => $this->getPermission(),
                'remMeFlag' => $this->getRemMeFlag()
            );
        }

        // Setters
        function setFirstName($firstname) { $this->firstname = $firstname; }
        function setLastName($lastname) { $this->lastname = $lastname; }
        function setEmail($email) { $this->email = $email; }
        function setPassword($password) { $this->password = $password; }
        function setConfirmPwd($confirm) { $this->password = $confirm; }
        function setPermission($permission) { $this->permission = $permission; }
        function setRemMeFlag($remMeFlag) { $this->remMeFlag = $remMeFlag; }
        
        
        
        // Aux methods
    
        function getFullName($user) {
            return $user->getFirstName() . ' ' . $user->getLastName();
        }
    
        function isPasswordWeak($password) {
            return strlen($password) < 8;
        }
    
        function isPasswordValid($password, $confirmPwd) {
            return $password == $confirmPwd;
        }
        
        function isEmailValid() {
            return !preg_match($this->emailregex, $this->email);
        }
    }

?>