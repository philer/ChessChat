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
 * Alias function for easy use in templates
 * outputs the appropriate value for a language variable.
 * @var string
 */
function lang($langVar) {
	echo Core::getLanguage()->getLanguageItem($langVar);
}

/**
 * Alias function for safely handing string parameters
 * @var 	string 	$str
 * @return 	string
 */
function escapeString($str) {
	return Core::getDB()->escapeString($str);
}
