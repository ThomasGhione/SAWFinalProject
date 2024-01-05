<?php

    class sessionManager {

        function __construct() {
            session_start();
        }

        /* Getter methods*/

        function getEmail() {
            return $_SESSION["email"];
        }

        function getPermission() {
            return $_SESSION["permission"];
        }

        function getSessionVariable($name) {
            return $_SESSION[$name] ?? null;
        }

        /* Setter methods */

        function setEmail($email) {
            $_SESSION["email"] = htmlspecialchars($email);
        }

        function setPermission($permission) {
            $_SESSION["permission"] = htmlspecialchars($permission);
        }

        function setNewsletter($newsletter) {
            $_SESSION["newsletter"] = htmlspecialchars($newsletter);
        }


        /* Methods */

        function setSessionVariables($email, $permission, $newsletter) {
            $this->setEmail($email);
            $this->setPermission($permission);
            $this->setNewsletter($newsletter);
        }

        function endSession() {
            session_unset();
            session_destroy();
        }



        function isSessionSet() {
            return isset($_SESSION["email"]);
        }

        function isAdmin() {
            return isset($_SESSION["permission"]) && ($_SESSION["permission"] == "admin");
        }
    }


?>