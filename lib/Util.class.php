<?php

/**
 * TODO
 */
class Util {
	
	/**
	 * Creates route array from PATH_INFO.
	 * @return 	array<string>
	 */
	public static function getRoute() {
		if (isset($_SERVER['PATH_INFO'])) {
			$route = explode('/',trim($_SERVER['PATH_INFO'],'/ '));
		}
		return (isset($route) && $route[0] !== '') ? $route : array();
	}
	
	/**
	 * Convenience function for easy use in templates etc,
	 * returns an absolute url for a given route.
	 * @param 	string 	$route
	 * @return 	string
	 */
	public static function url($route) {
		return HOST . 'index.php/' . $route;
	}
	
	/**
	 * TODO
	 * @return 	string
	 */
	public static function cookiePath() {
		return str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
	}
	
	/**
	 * Sets a cookie. Cookienames are prepended with
	 * a global prefix.
	 * @see 	global.conf.php COOKIE_PREFIX
	 * @param 	string 	$name 	global prefix will be prepended automatically
	 * @param 	string 	$value
	 * @param 	integer $expiration 	optional expiration timestamp
	 */
	public static function setCookie ($name, $value, $expiration = null) {
		setcookie(
			COOKIE_PREFIX . $name,
			$value,
			is_null($expiration) ? COOKIE_DAYS*3600*24 + NOW : $expiration,
		 	self::cookiePath()
		);
	}
	
	/**
	 * Deletes a cookie of specified name.
	 * @see 	Util::setCookie()
	 * @param 	string 	$name 	global prefix will be prepended automatically
	 */
	public static function deleteCookie($name) {
		self::setCookie($name, '', 1);
	}
	
	/**
	 * Returns the value of the given cookie or null if it doesn't exist.
	 * @param 	string 	$name 	global prefix will be prepended automatically
	 * @return 	mixed
	 */
	public static function getCookie($name) {
		return isset($_COOKIE[COOKIE_PREFIX . $name]) ? $_COOKIE[COOKIE_PREFIX . $name] : null;
	}
	
	/**
	 * Alias function for safely handing string parameters
	 * @var 	string 	$str
	 * @return 	string
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
	 * Generates a random hash
	 * TODO
	 * @return string
	 */
	public static function getRandomHash() {
		// TODO
		return sha1(openssl_random_pseudo_bytes(24));
	}
	
	/**
	 * Takes a timestamp and returns a user friendly
	 * string representation like '5 minutes ago'.
	 * TODO dynamic lang variables
	 * @param 	integer 	UNIX timestamp
	 * @return 	string
	 */
	public static function formatTime($timestamp) {
		if (NOW-$timestamp <= 60) {
			return 'now';
		} elseif (60 >= $minutes = (integer) ((NOW-$timestamp) / 60)) {
			return $minutes . ' minutes ago';
		} elseif (24 >= $hours = (integer) ((NOW-$timestamp) / (60*24))) {
			return $hours . ' hours ago';
		} elseif (3600*24*2 >= NOW-$timestamp) {
			return 'yesterday';
		} else {
			return date('Y-m-d', $timestamp);
		}
	}
	
}
