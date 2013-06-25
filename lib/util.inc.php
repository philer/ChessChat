<?php
/**
 * Define utility, alias and magical functions here
 */

/**
 * Magic function autoload is called whenever a
 * unknown Class is instanciated ("new").
 * It checks for a <classname>.class|interface.php file in
 * all known directories (inside lib/) and includes it
 */
function __autoload($className) {
	
	// add new directories here
	$dirs = array(
		'lib/',
		'lib/controller/',
		'lib/model/',
		'lib/chess/',
		'lib/exception/',
	);
	
	foreach ($dirs as $dir) { // try all directories for class.php
		if (file_exists(ROOT_DIR.$dir.$className.'.class.php')) {
			require_once(ROOT_DIR.$dir.$className.'.class.php');
			return;
		}
	}
	foreach ($dirs as $dir) { // try all directories for if.php
		if (file_exists(ROOT_DIR.$dir.$className.'.if.php')) {
			require_once(ROOT_DIR.$dir.$className.'.if.php');
			return;
		}
	}
	
	//throw new ClassNotFoundException($className);
}

/**
 * Alias function for safely handing string parameters
 * @var 	string 	$str
 * @return 	string
 */
function esc($str) {
	return Core::getDB()->escapeString($str);
}

/**
 * Compares two strings for equality.
 * This function is secure against timing attacks
 * @see TODO
 * @param 	string 	$str1
 * @param 	string 	$str1
 * @return 	boolean
 */
function safeEquals($str1, $str2) {
	// TODO 
	return false;
}
