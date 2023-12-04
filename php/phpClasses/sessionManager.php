<?php

    class sessionManager {

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
            $this->email = $email;
        }

        function setPermission($permission) {
            $this->permission = $permission;
        }


        /* Methods */

        function startSession() {
            session_start();
        }

        function setSessionVariables($email, $permission) {
            $_SESSION['email'] = $email;
            $_SESSION['permission'] = $permission;
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