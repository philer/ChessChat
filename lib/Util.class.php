<?php

/**
 * Collection of static utility functions
 * @author  Philipp Miller
 */
class Util {
    
    /**
     * Creates route array from PATH_INFO.
     * @see  routes.conf.php
     * @return     array<string>
     */
    public static function getRoute() {
        if (isset($_SERVER['PATH_INFO'])) {
            $pathInfo = trim($_SERVER['PATH_INFO'], '/ ');
        
            require_once(ROOT_DIR . 'config/routes.conf.php');
            if (array_key_exists($pathInfo, $routes)) {
                $route = explode('/', $routes[$pathInfo]);
            } else {
                $route = explode('/', $pathInfo);
            }
        }
        return (isset($route) && $route[0] !== '') ? $route : array();
    }
    
    /**
     * Returns the absolute request path
     * e.g. this/path/ from http://example.com/this/path/index.php
     * @return     string
     */
    public static function getRequestPath() {
        return dirname($_SERVER['SCRIPT_NAME']) . '/';
    }
    
    /**
     * Returns the URL for the site including protocol
     * @return string
     */
    public static function getBaseUrl() {
        return 'http://' . HOST . self::getRequestPath();
    }
    
    /**
     * Convenience function for easy use in templates etc,
     * returns an absolute url for a given route.
     * @param     string     $route
     * @return     string
     */
    public static function url($route) {
        return self::getBaseUrl()
             . (SEO_URL ? '' : 'index.php/')
             . $route;
    }
    
    /**
     * Sets a cookie. Cookienames are prepended with
     * a global prefix.
     * @see     global.conf.php COOKIE_PREFIX
     * @param     string     $name     global prefix will be prepended automatically
     * @param     string     $value
     * @param     integer $expiration     optional expiration timestamp
     */
    public static function setCookie ($name, $value, $expiration = null) {
        setcookie(
            COOKIE_PREFIX . $name,
            $value,
            is_null($expiration) ? COOKIE_DAYS*3600*24 + NOW : $expiration,
             self::getRequestPath()
        );
    }
    
    /**
     * Deletes a cookie of specified name.
     * @see     Util::setCookie()
     * @param     string     $name     global prefix will be prepended automatically
     */
    public static function deleteCookie($name) {
        self::setCookie($name, '', 1);
    }
    
    /**
     * Returns the value of the given cookie or null if it doesn't exist.
     * @param     string     $name     global prefix will be prepended automatically
     * @return     mixed
     */
    public static function getCookie($name) {
        return isset($_COOKIE[COOKIE_PREFIX . $name]) ? $_COOKIE[COOKIE_PREFIX . $name] : null;
    }
    
    /**
     * Alias function for safely handing string parameters
     * @var     string     $str
     * @return     string
     */
    public static function esc($str) {
        return Core::getDB()->escapeString($str);
    }
    
    /**
     * Compares two strings for equality.
     * This function is secured against timing attacks
     * @see  http://codahale.com/a-lesson-in-timing-attacks/
     * @see  http://crackstation.net/hashing-security.htm#slowequals
     * @param  string $str1
     * @param  string $str2
     * @return boolean
     */
    public static function safeEquals($str1, $str2) {
        $str1l = strlen($str1);
        $str2l = strlen($str2);
        
        $diff = $str1l ^ $str2l;
        for ($i = 0 ; $i < $str1l & $i < $str2l ; $i++) {
            $diff |= ord($str1[$i]) ^ ord($str2[$i]);
        }
        return $diff === 0;
    }
    
    /**
     * Generate the encrypted string using blowfish.
     * Blowfishtype 2y is compatible with PHP 5.3.7+
     * @param  string $password
     * @param  string $salt
     * @return string           blowfish hash
     */
    public static function encrypt($password) {
        return crypt(
            $password,
            '$2y$08$' . self::getRandomString(21, './') . '$'
        );
    }
    
    /**
     * Checks a cleartext password against a full length
     * blowfish hash.
     * @param  string $password
     * @param  string $blowfish
     * @return boolean
     */
    public static function checkPassword($password, $blowfish) {
        return self::safeEquals(
            substr(crypt(
                    $password,
                    substr($blowfish, 0, 28) . '$'
                ), 28),
            substr($blowfish, 28)
        );
    }
    
    /**
     * Generates a cryptographicaly secure random integer
     * between $min and $max (inclusive)
     * @see http://php.net/manual/en/function.openssl-random-pseudo-bytes.php#104322
     * 
     * @param  integer $min
     * @param  integer $max
     * @return integer
     */
    public static function rand($min, $max) {
        $range = $max - $min;
        if ($range <= 0) return $min;
        $log    = log($range, 2);
        $bytes  = (int) ($log / 8)   + 1; // length in bytes
        $bits   = (int)  $log        + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd > $range);
        return $min + $rnd;
    }
    
    /**
     * Signum function (math).
     * Returns 1 for positive numbers,
     * -1 for negative and 0 for 0.
     * @return integer
     */
    public static function sign($number) {
        if ($number > 0) return 1;
        if ($number < 0) return -1;
        return 0;
    }
    
    /**
     * Generates a random string containing
     * upper- and lowercase letters and digits.
     * Additional characters may be specified.
     * maxLength may be omitted, making $minLength the actual length.
     * @param  integer $minLength       defaults to 16
     * @param  integer $maxLength       defaults to 0
     * @param  string  $additionalChars
     * @return string
     */
    public static function getRandomString($minLength = 16, $maxLength = 0, $additionalChars = '') {
        if (is_string($maxLength)) {
            $length = $minLength;
            $additionalChars .= $maxLength;
        } else {
            $length = self::rand($minLength, $maxLength);
        }
        
        $chars = 'abcdefghijklmnopqrstuvwxyz'
               . 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'
               . '0123456789'
               . $additionalChars;
        
        $str = '';
        for ($c=0 ; $c<$length ; $c++) {
            $str .= $chars[ self::rand(0, strlen($chars)-1) ];
        }
        return $str;
    }
    
    /**
     * Generates a hash for identification use in URLs
     * (like youtube or pastbin.com)
     * @param  string $string string to be hashed
     * @param  integer $length length of the desired hash
     * @return string
     */
    public static function urlHash($string, $length) {
        return substr(
                str_replace(
                        array('/','+','='),
                        '',
                        base64_encode( md5($string) )
                    ),
                0,
                $length
            );
    }
    
    /**
     * Takes a timestamp and returns a user friendly
     * string representation like '5 minutes ago'.
     * @param     integer     UNIX timestamp
     * @return     string
     */
    public static function formatTime($timestamp) {
        if (NOW-$timestamp <= 60) {
            return 'now';
        }
        if (60 >= $minutes = (integer) ((NOW-$timestamp) / 60)) {
            if ($minutes == 1) {
                return Core::getLanguage()->getLanguageItem('time.oneminuteago');
            }
            return Core::getLanguage()->getLanguageItem('time.xminutesago', array('x' => $minutes));
        }
        if (24 >= $hours = (integer) ((NOW-$timestamp) / (60*24))) {
            if ($hours == 1) {
                return Core::getLanguage()->getLanguageItem('tíme.onehourago');
            }
            return Core::getLanguage()->getLanguageItem('time.xhoursago', array('x' => $hours));
        }
        if (date('Ymd', NOW-3600*24) === date('Ymd', $timestamp)) {
            return Core::getLanguage()->getLanguageItem('time.yesterday');
        }
        return date(Core::getLanguage()->getLanguageItem('time.dateformat'),
                $timestamp);
    }
    
}
