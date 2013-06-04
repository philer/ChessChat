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
	 * Array contains route
	 * @var array
	 */
	protected static $route = array();
	
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
		
		//TODO user/session
		
		$this->setLanguage();
		
		$this->setRoute();
		
		// TODO
		// ... new AjaxRequestHandler(); // distribute tasks
		// or
		// ... new RequestHandler(); // distribute tasks
		// Request types: plain page (impressum), game, ajax, (userprofile)
		/*if (self::$route[0] == "ajax") {
			new AjaxRequestHandler();
		}
		if (self::$route[0] == "game") {
			new GameRequestHandler(); // TODO change
		}*/
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
	 * Creates route array from PATH_INFO.
	 */
	protected function setRoute() {
		if (isset($_SERVER['PATH_INFO'])) {
			self::$route = explode('/',trim($_SERVER['PATH_INFO'],'/ '));
		}
	}
	
	/**
	 * Returns array containing route.
	 * @return array
	 */
	public static function getRoute() {
		return self::$route;
	}
	
	/**
	 * Initiates language object.
	 */
	protected function setLanguage() {
		if (isset($_GET['lang'])) {
			// use specifically requested language
			self::$language = new Language($_GET['lang']);
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
