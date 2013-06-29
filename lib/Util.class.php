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
		return !isset($route) ? array() : $route;
	}
	
	/**
	 * TODO
	 * @return 	string
	 */
	public static function cookiePath() {
		return str_replace('index.php', '', $_SERVER['SCRIPT_NAME']);
	}
	
	/**
	 * Compares two strings for equality.
	 * This function is secure against timing attacks
	 * @see TODO
	 * @param 	string 	$str1
	 * @param 	string 	$str1
	 * @return 	boolean
	 */
	public static function safeEquals($str1, $str2) {
		// TODO 
		return $str1 == $str2;
	}
	
	public static function getRandomHash() {
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
