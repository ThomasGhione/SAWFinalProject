<?php

    class User {
        private $firstName;
        private $lastName;
        private $username;
        private $email;
        private $password;
        // optional values
        private $pfp;   // TODO add a default pfp
        private $bio;
        private $gender;
        private $birthday;
        
        static private $emailregex = "/\S+@\S+\.\S+/";  // TODO correggere regex per email

        function __construct($firstName, $lastName, $username, $password, $confirmpw, $pfp, $bio, $gender, $birthday) {

            // TODO add a catch inside  dbManager->registerUser() to handle these construct's throws

            if ( empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmpwd) ) {
                error_log('Error: empty parameters');
                throw new Exception('Empty parameters passed to the form');
            }

            if ( !$this->isEmailValid() ) {
                error_log('Error: email is not valid');
                throw new Exception('Email is not valid');
            }

            if ( !$this->isPasswordStrong() ) {
                error_log('Error: password is not strong enough');
                throw new Exception('Password is not strong enough');
            }

            if ( !$this->isPasswordValid($password, $confirmpw) ) {
                error_log('Error: passwords do not match');
                throw new Exception('Passwords do not match');
            }

            
            

            $this->firstName = trim($firstName);
            $this->lastName = trim($lastName);
            $this->username = trim($username);
            $this->password = password_hash(trim($password), PASSWORD_DEFAULT);

            $this->pfp = $pfp; //TODO add a default pfp

            if ( $bio != null )
                $this->bio = $bio;
            if ($gender != null)
                $this->gender = $gender;
            if ($birthday != null)
                $this->birthday = $birthday;
        }

        function __destruct() {
            $this->firstName = null;
            $this->lastName = null;
            $this->username = null;
            $this->email = null;
            $this->password = null;

            $this->pfp = null;
            
            $this->bio = null;
            $this->gender = null;
            $this->birthday = null;
        }


        // Getters
        function getFirstName() { return $this->firstName; }
        function getLastName() { return $this->lastName; }
        function getUsername() { return $this->username; }
        function getEmail() { return $this->email; }
        function getPassword() { return $this->password; }
        function getPfp() { return $this->pfp; }
        function getBio() { return $this->bio; }
        function getGender() { return $this->gender; }
        function getBirthday() { return $this->birthday; }

        function getUser() {
            return array(
                'firstName' => $this->getFirstName(),
                'lastName' => $this->getLastName(),
                'username' => $this->getUsername(),
                'email' => $this->getEmail(),
                'password' => $this->getPassword(),
                'pfp' => $this->getPfp(),
                'bio' => $this->getBio(),
                'gender' => $this->getGender(),
                'birthday' => $this->getBirthday()
            );
        }

        // Setters
        function setFirstName($firstName) { $this->firstName = $firstName; }
        function setLastName($lastName) { $this->lastName = $lastName; }
        function setUsername($username) { $this->username = $username; }
        function setEmail($email) { $this->email = $email; }
        function setPassword($password) { $this->password = $password; }
        function setConfirmPw($confirmpwd) { $this->password = $confirmpwd; }
        function setPfp($pfp) { $this->pfp = $pfp; }
        function setBio($bio) { $this->bio = $bio; }
        function setGender($gender) { $this->gender = $gender; }
        function setBirthday($birthday) { $this->birthday = $birthday; }
        
        
        
        // Aux methods
    
        function getFullName($user) {
            return $user->getFirstName() . ' ' . $user->getLastName();
        }
    
        function isPasswordStrong() {
            return strlen($this->password) >= 8;
        }
    
        function isPasswordValid($password, $confirmpw) {
            return $password == $confirmpw;
        }
        
        function isEmailValid() {
            return !preg_match($this->emailregex, $this->email);
        }
    }


?>