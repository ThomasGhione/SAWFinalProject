<?php

    class User {
        private $firstName;
        private $lastName;
        private $userName;
        private $email;
        private $password;
        // optional values
        private $gender;
        private $birthday;
        private $permission;
        
        static private $emailregex = "/\S+@\S+\.\S+/";  // TODO correggere regex per email

        function __construct($login, $email, $password,
                             $firstName = null, $lastName = null, $userName = null,
                             $confirmPwd = null, $gender = null, $birthday = null) {

            if (empty($email) || empty($password)) {
                error_log('Error: empty parameters');
                throw new Exception('Empty parameters passed to the form');
            }

            if (!$this->isEmailValid()) {
                error_log('Error: email is not valid');
                throw new Exception('Email is not valid');
            }

            if ($login)
                $this->cLogin($email, $password);
            else
                $this->cRegister($firstName, $lastName, $userName, $email, $password, $confirmPwd, $gender, $birthday);
        }

        function __destruct() {
            $this->firstName = null;
            $this->lastName = null;
            $this->userName = null;
            $this->email = null;
            $this->password = null;

            $this->gender = null;
            $this->birthday = null;
        }

        // Constructor extensions

        public function cLogin($email, $password) {
            $this->email = trim($email);
            $this->password = trim($password);
        }

        public function cRegister($firstName, $lastName, $userName, $email, $password, $confirmPwd, $gender, $birthday) {

            if (empty($firstName) || empty($lastName) || empty($confirmPwd)) {
                error_log('Error: empty parameters');
                throw new Exception('Empty parameters passed to the form');
            }

            if ($this->isPasswordWeak($password)) {
                error_log('Error: password is not strong enough');
                throw new Exception('Password is not strong enough');
            }

            if (!$this->isPasswordValid($password, $confirmPwd)) {
                error_log('Error: passwords do not match');
                throw new Exception('Passwords do not match');
            }

            $this->firstName = trim($firstName);
            $this->lastName = trim($lastName);
            $this->userName = trim($userName);
            $this->email = trim($email);
            $this->password = password_hash(trim($password), PASSWORD_DEFAULT);

            if ($gender !== null)
                $this->gender = $gender;
            if ( $birthday !== null ) 
                $this->birthday = date('Y-m-d', strtotime($birthday));
        }

        // Getters
        function getFirstName() { return $this->firstName; }
        function getLastName() { return $this->lastName; }
        function getUserName() { return $this->userName; }
        function getEmail() { return $this->email; }
        function getPassword() { return $this->password; }
        function getGender() { return $this->gender; }
        function getBirthday() { return $this->birthday; }
        function getPermission() { return $this->permission; }

        function getUser() {
            return array(
                'firstName' => $this->getFirstName(),
                'lastName' => $this->getLastName(),
                'userName' => $this->getUserName(),
                'email' => $this->getEmail(),
                'password' => $this->getPassword(),
                'gender' => $this->getGender(),
                'birthday' => $this->getBirthday(),
                'permission' => $this->getPermission()
            );
        }

        // Setters
        function setFirstName($firstName) { $this->firstName = $firstName; }
        function setLastName($lastName) { $this->lastName = $lastName; }
        function setUserName($userName) { $this->userName = $userName; }
        function setEmail($email) { $this->email = $email; }
        function setPassword($password) { $this->password = $password; }
        function setConfirmPwd($confirmPwd) { $this->password = $confirmPwd; }
        function setGender($gender) { $this->gender = $gender; }
        function setBirthday($birthday) { $this->birthday = $birthday; }
        function setPermission($permission) { $this->permission = $permission; }
        
        
        
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