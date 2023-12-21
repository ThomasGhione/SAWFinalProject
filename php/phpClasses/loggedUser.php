<?php

    require("./dbManager.php");

    class loggedUser {

        private $firstname;
        private $lastname;
        private $email;
        private $username;
        private $pfp;
        private $gender;
        private $birthday;
        private $description;

        function __construct ($email) {
            $dbManager = new dbManager();

            $dbManager->dbQueryWithParams("SELECT * FROM users WHERE users = ?", "s", [$email]);


        }

        function setFirstname() {

        }

        function setLastname() {
            
        }

        function setEmail() {
            
        }

        function setUsername() {
            
        }

        function setPfp() {
            
        }

        function setGender() {
            
        }

        function setBirthday() {

        }

        function setDescription() {

        }


        function getFirstname() {

        }

        function getLastname() {
            
        }

        function getEmail() {
            
        }

        function getUsername() {
            
        }

        function getPfp() {
            
        }

        function getGender() {
            
        }

        function getBirthday() {

        }

        function getDescription() {
            
        }

    }

















?>