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
	);
	
	foreach ($dirs as $dir) { // try all directories for class.php
		if (file_exists(ROOT_DIR.$dir.$className.'.class.php')) {
			require_once(ROOT_DIR.$dir.$className.'.class.php');
			return;
		}
	}
	foreach ($dirs as $dir) { // try all directories for interface.php
		if (file_exists(ROOT_DIR.$dir.$className.'.interface.php')) {
			require_once(ROOT_DIR.$dir.$className.'.interface.php');
			return;
		}
	}
	
	throw new ClassNotFoundException($className);
}

/**
 * alias function for easy use in templates
 * @var string
 */
function lang($langVar) {
	echo Core::getLanguage()->getLanguageItem($langVar);
}
