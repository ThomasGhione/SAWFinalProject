<?php

    class sessionManager {
        function __construct() { session_start(); }

        /* Getter methods*/
        function getEmail(): string { return $_SESSION["email"]; }
        function getPermission() { return $_SESSION["permission"]; }
        function getSessionVariable($name) { return $_SESSION[$name] ?? null; }

        /* Setter methods */
        function setEmail(string $email): void { $_SESSION["email"] = htmlspecialchars($email); }
        function setPermission(&$permission): void { $_SESSION["permission"] = htmlspecialchars($permission); }
        function setNewsletter($newsletter): void { $_SESSION["newsletter"] = $newsletter; }


        /* Methods */

        function setSessionVariables(string $email, string $permission, $newsletter): void {
            $this->setSessionVariablesEmailAndPermission($email, $permission);
            $this->setNewsletter($newsletter);
        }

        function setSessionVariablesEmailAndPermission(string $email, string $permission) {
            $this->setEmail($email);
            $this->setPermission($permission);
        }

        function endSession(): void {
            $_SESSION = array();
            session_destroy();
        }

        // returns true if the session is set, false otherwise
        function isSessionSet(): bool {
            return isset($_SESSION["email"]);
        }

        // returns true if the user is an admin, false otherwise
        function isAdmin(): bool {
            return isset($_SESSION["permission"]) && ($_SESSION["permission"] == "admin");
        }
    }


?>