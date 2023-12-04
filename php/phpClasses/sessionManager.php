<?php

    class Session {

        /* Fields */

        private $email;
        private $permission;


        /* Constructors*/

        function __construct($email, $permission){
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

        function getSessionVariable($name) {
            return $_SESSION[$name] ?? null;
        }

        /* Setter methods */

        function setEmail($email) {
            $this->email = $email;
        }

        function setPermission($permission) {
            $this->permission = $permission;
        }


        /* Methods */

        function startSession() {
            session_start();
        }

        function setSessionVariables() {
            $_SESSION['email'] = $this->email;
            $_SESSION['permission'] = $this->permission;
        }

        function endSession() {
            session_unset();
            session_destroy();
        }

        function isSessionSet($name) {
            return isset($_SESSION[$name]);
        }

        function sessionStatus() {
            return session_status();
        }

        function isSessionStarted() {
            return session_status() == PHP_SESSION_ACTIVE;
        }

        function isSessionExpired() { // TODO check if this works
            return isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800);
        }

        function updateSession() { // TODO check if this works 
            $_SESSION['LAST_ACTIVITY'] = time();
        }

    }


?>