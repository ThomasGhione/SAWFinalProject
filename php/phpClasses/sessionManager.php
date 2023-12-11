<?php

    class sessionManager {

        function __construct() {
            session_start();
        }

        /* Getter methods*/

        function getEmail() {
            return $_SESSION['email'];
        }

        function getPermission() {
            return $_SESSION['permission'];
        }

        function getSessionVariable($name) {
            return $_SESSION[$name] ?? null;
        }

        /* Setter methods */

        function setEmail($email) {
            $_SESSION['email'] = $email;
        }

        function setPermission($permission) {
            $_SESSION['permission'] = $permission;
        }


        /* Methods */

        function setSessionVariables($email, $permission) {
            $this->setEmail($email);
            $this->setPermission($permission);
        }

        function endSession() {
            session_unset();
            session_destroy();
        }



        function isSessionSet() {
            return isset($_SESSION['email']);
        }

        function isAdmin() {
            return isset($_SESSION['permission']) && ($_SESSION['permission'] == 'admin');
        }




        // Methods not yet used
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