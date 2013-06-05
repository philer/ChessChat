<?php

// use custom exception and error handling
set_exception_handler(array('Core','exceptionHandler'));
set_error_handler(array('Core','errorHandler'));

// utility functions
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
	 * @var Controller
	 */
	protected static $controller = null;
	public static function getController() { return self::$controller; } // debugging
	
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
		
		// let's identify that request.
		if (empty(self::$route)) {
			
			// no route at all, use default page
			self::$controller  = new IndexController();
			self::$controller->handleStandaloneRequest();
			
		} else if (self::$route[0] === "ajax") {
				
			// ajax request route
			self::$route = array($_POST);
			$controllerClass = array_shift(self::$route); //."Controller";
			
			if (class_exists($controllerClass)
					&& is_subclass_of($controllerClass,'AjaxController')) {
				
				self::$controller = new $controllerClass();
				
			} else throw new InvalidAjaxException();
			
			self::$controller->handleAjaxRequest();
				
		} else {
			
			// regular request route
			$controllerClass = self::$route[0]."Controller";
			if(class_exists("ddd"))echo "test";
			if (class_exists($controllerClass)
					&& is_subclass_of($controllerClass,'StandaloneController')) {
				
				array_shift(self::$route);
				self::$controller = new $controllerClass();
				
			} else if (Game::hashPregMatch(self::$route[0])) {
				
				// special feature: shorter urls for Game
				self::$controller = new GameController();
				
			} else throw new PageNotFoundException();
			
			self::$controller->handleStandaloneRequest();
			
		}
		
	}
	
	/**
	 * Reads database config and creates the database object
	 */
	protected function setDB() {
		$dbHost = $dbUser = $dbPass = $dbName = '';
		require_once(ROOT_DIR."config/db.conf.php");
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
		if (method_exists($e,'toTpl')) $e->toTpl();
		else echo $e;
		exit;
	}
	
	/**
	 * Better error handling: Throws an exception.
	 * @see http://www.php.net/manual/en/errorfunc.examples.php
	 */
	public static function errorHandler($errno, $errmsg, $file, $line) {
		$errortypes = array (
			E_ERROR		=> 'Error',
			E_WARNING	=> 'Warning',
			E_PARSE		=> 'Parse error',
			E_NOTICE	=> 'Notice',
			);
		if (array_key_exists($errno,$errortypes))
			$type = $errortypes[$errno];
		else $type = "errorcode ".$errno;
		
		$errmsg = $type." in file ".$file.":$line\n".$errmsg;
		throw new FatalException($errmsg);
	}
}
