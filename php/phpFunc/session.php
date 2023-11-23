<?php

    class Session {

        /* Fields */

        private $firstname;
        private $lastname;
        private $email;
        private $permission;


        /* Constructors*/

        function __construct($firstname, $lastname, $email, $permission){
            $this->firstname = $firstname;
            $this->lastname = $lastname;
            $this->email = $email;
            $this->permission = $permission;
        }


        /* Getter methods*/

        function getFirstname() {
            return $this->firstname;
        }

        function getLastname() {
            return $this->lastname;
        }

        function getEmail() {
            return $this->email;
        }

        function getPermission() {
            return $this->permission;
        }


        /* Setter methods */

        function setFirstname($firstname) {
            $this->firstname = $firstname;
        }

        function setLastname($lastname) {
            $this->lastname = $lastname;
        }        

        function setEmail($email) {
            $this->email = $email;
        }

        function setPermission($permission) {
            $this->permission = $permission;
        }


        /* Methods */

        

    }


?>