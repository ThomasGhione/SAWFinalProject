<?php

    class cookieManager {
        public function setCookie($name, &$value, &$expiry): void {
            setcookie(htmlspecialchars($name), htmlspecialchars($value), time() + $expiry, "/");
        }
    
        public function getCookie($name) {
            return $_COOKIE[$name] ?? null;
        }
    
        public function deleteCookie($name): void {
            setcookie($name, "", time() - 3600, "/");
        }
    
        public function isCookieSet($name): bool {
            return isset($_COOKIE[$name]);
        }        
    }

?>