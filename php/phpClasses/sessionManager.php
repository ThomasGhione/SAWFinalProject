<?php

    class Session {

        /* Fields */

        private $email;
        private $permission;


        /* Constructors*/

        function __construct($firstname, $lastname, $email, $permission){
            $this->email = $email;
            $this->permission = $permission;
        }


        /* Getter methods*/

        function getEmail() {
            return $this->email;
        }

        function getPermission() {
            return $this->permission;
        }


        /* Setter methods */

        function setEmail($email) {
            $this->email = $email;
        }

        function setPermission($permission) {
            $this->permission = $permission;
        }


        /* Methods */

        

    }


?>