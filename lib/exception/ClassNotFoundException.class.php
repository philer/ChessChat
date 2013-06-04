<?php

/**
 * OBSOLETE
 * throw this exception when an expected class could not be found in the filesystem.
 * @see __autoload (lib/util.inc.php)
 * @author Philipp Miller
 */
class ClassNotFoundException extends FatalException {
	
	public function __construct($className) {
		$this->title = "Class Not Found";
		parent::__construct("Class ".$className.".class.php could not be found in any known directory.");
	}

}
