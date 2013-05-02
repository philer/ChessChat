<?php

require_once(ROOT_DIR.'lib/util.inc.php');

class Core {
	
	/**
	 * Language object
	 * @var Language
	 */
	protected static $language;
	
	
	public function __construct() {
		$this->setLanguage();
	}
	
	protected function setLanguage() {
		// TODO $_SERVER['HTTP_ACCEPT_LANGUAGE']
		
		// use just english for now)
		self::$language = new Language("en");
	}
	
	public static function getLanguage() {
		return self::$language;
	}
}
