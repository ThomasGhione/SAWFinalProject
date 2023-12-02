<?php

    class cookieManager {
        public function setCookie($name, $value, $expiry) {
            setcookie($name, $value, time() + $expiry, "/");
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

        public function updateCookie($name, $value, $expiry) {
            $this->setCookie($name, $value, $expiry);
        }

        public function extendCookie($name, $expiry) {
            if ($this->isCookieSet($name)) {
                $this->setCookie($name, $this->getCookie($name), $expiry);
            }
        }

        public function clearAllCookies() {
            foreach ($_COOKIE as $name => $value) {
                $this->deleteCookie($name);
            }
        }
    }

?>