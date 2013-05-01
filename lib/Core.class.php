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
		// $_SERVER['HTTP_ACCEPT_LANGUAGE']
		// TODO (use just english for now)
		$this->language = new Language('en');
	}
}
