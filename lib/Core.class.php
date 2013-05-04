<?php

require_once(ROOT_DIR.'lib/util.inc.php');

class Core {
	
	/**
	 * Array contains requested Path
	 * @var array
	 */
	protected static $request;
	
	/**
	 * Language object
	 * @var Language
	 */
	protected static $language;
	
	/**
	 * Calls all core init methods
	 */
	public function __construct() {
		$this->wrapRequest();
		$this->setLanguage();
		// TODO
		// ... new AjaxRequestHandler(); // distribute tasks
		// or
		// ... new RequestHandler(); // distribute tasks
	}
	
	/**
	 * Reads all request parameters from
	 * $_GET, $_POST and $_SERVER['PATH_INFO']
	 * and puts them in self::$request
	 */
	protected function wrapRequest() {
		$request = array();
		if (isset($_SERVER['PATH_INFO'])) {
			$request = explode('/',ltrim($_SERVER['PATH_INFO'],'/ '));
		}
		self::$request = array_merge($request,$_GET,$_POST);
	}
	
	/**
	 * Returns array containing request parameters
	 * @return array
	 */
	public static function getRequest() {
		return self::$request;
	}
	
	/**
	 * Initiates Language Object
	 */
	protected function setLanguage() {
		if (isset(self::$request['lang'])) {
			// use specifically requested language
			self::$language = new Language(self::$request['lang']);
		} else {
			// let the Language class determine the appropriate language
			self::$language = new Language();
		}
	}
	
	/**
	 * Returns Language Object for the selected Language
	 * @return Language
	 */
	public static function getLanguage() {
		return self::$language;
	}
}
