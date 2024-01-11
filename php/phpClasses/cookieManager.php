<?php

    class cookieManager {
        function setCookie($name, &$value, &$expiry): void {
            setcookie(htmlspecialchars($name), htmlspecialchars($value), time() + $expiry, "/");
        }
    
        function getCookie($name) {
            return $_COOKIE[$name] ?? null;
        }
    
        function deleteCookie($name): void {
            setcookie($name, "", time() - 3600, "/");
        }
    
        function isCookieSet($name): bool {
            return isset($_COOKIE[$name]);
        }        
    }

?>