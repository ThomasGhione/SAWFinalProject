<?php

    class sessionManager {

        function __construct() {
            session_start();
        }

        /* Getter methods*/
        function getEmail(): string { return $_SESSION["email"]; }
        function getPermission() { return $_SESSION["permission"]; }
        function getSessionVariable($name) { return $_SESSION[$name] ?? null; }

        /* Setter methods */
        function setEmail(string $email): void { $_SESSION["email"] = htmlspecialchars($email); }
        function setPermission(&$permission): void { $_SESSION["permission"] = htmlspecialchars($permission); }
        function setNewsletter($newsletter): void { $_SESSION["newsletter"] = htmlspecialchars($newsletter); }


        /* Methods */

        function setSessionVariables(string $email, string $permission, $newsletter): void {
            $this->setEmail($email);
            $this->setPermission($permission);
            $this->setNewsletter($newsletter);
        }

        function endSession(): void {
            $_SESSION = array();
            session_destroy();
        }



        function isSessionSet(): bool {
            return isset($_SESSION["email"]);
        }

        function isAdmin(): bool {
            return isset($_SESSION["permission"]) && ($_SESSION["permission"] == "admin");
        }
    }


?>