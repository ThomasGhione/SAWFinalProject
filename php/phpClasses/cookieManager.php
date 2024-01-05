<?php

    class cookieManager {
        public function setCookie($name, $value, $expiry) {
            setcookie(htmlspecialchars($name), htmlspecialchars($value), time() + $expiry, "/");
        }
    
        public function getCookie($name) {
            return $_COOKIE[$name] ?? null;
        }
    
        public function deleteCookie($name) {
            setcookie($name, "", time() - 3600, "/");
        }
    
        public function isCookieSet($name) {
            return isset($_COOKIE[$name]);
        }        
    }

?>