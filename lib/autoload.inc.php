<?php
/**
 * Magic function autoload is called whenever an
 * unknown class is requested.
 * It checks for a <classname>.(class|if).php file in
 * all known directories (inside lib/) and includes it.
 * @author Philipp Miller
 */
function __autoload($className) {
	
	// add new directories here
	$dirs = array(
		'lib/',
		'lib/controller/',
		'lib/model/',
		'lib/model/chess/',
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
