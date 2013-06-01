<?php

set_exception_handler(array('Core','exceptionHandler'));

require_once(ROOT_DIR.'lib/util.inc.php');

/**
 * The System Core initializes and coordinates all system components.
 * @author Philipp Miller
 */
class Core {
	
	/**
	 * The database object
	 * @var Database
	 */
	protected static $db = null;
	
	/**
	 * Array contains requested Path
	 * @var array
	 */
	protected static $request = array();
	
	/**
	 * Language object
	 * @var Language
	 */
	protected static $language = null;
	
	/**
	 * Calls all core init methods
	 */
	public function __construct() {
		
		$this->setDB();
		
		//TODO session
		
		$this->setLanguage();
		
		$this->wrapRequest();
		// TODO
		// ... new AjaxRequestHandler(); // distribute tasks
		// or
		// ... new RequestHandler(); // distribute tasks
	}
	
	/**
	 * Reads database config and creates the database object
	 */
	protected function setDB() {
		$dbHost = $dbUser = $dbPass = $dbName = '';
		include(ROOT_DIR."dbconfig.inc.php");
		self::$db = new Database($dbHost, $dbUser, $dbPass, $dbName);
	}
	
	/**
	 * Returns the database object
	 * @return Database
	 */
	public function getDB() {
		return self::$db;
	}
	
	/**
	 * TODO remove this -> session
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
	
	/**
	 * called automatically when an exception is thrown.
	 * @see set_exception_handler()
	 */
	public static function exceptionHandler(Exception $e) {
		if (method_exists($e,'show')) $e->show();
		else print $e;
		exit;
	}
}
