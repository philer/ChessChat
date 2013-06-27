<?php

/**
 * The System Core initializes and coordinates all system components.
 * @author Philipp Miller
 */
class Core {
	
	/**
	 * The Database object
	 * @var Database
	 */
	protected static $db = null;
	
	/**
	 * The User object
	 * @var User
	 */
	protected static $user = null;
	
	/**
	 * The Language object
	 * @var Language
	 */
	protected static $language = null;
	
	/**
	 * TemplateEngine takes care of equipping templates
	 * and sending them.
	 * @var TemplateEngine
	 */
	protected static $templateEngine = null;
	
	/**
	 * The Controller takes care of executing this request.
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
	 * Calls all core init methods
	 */
	public function __construct() {
		
		$this->setDB();
		
		//TODO user/session
		// self::$user = new User(1,"DU"); // Dummy User
		$this->setUser();
		
		$this->setLanguage();
		$this->setTemplateEngine();
				
		$this->setRoute();
		
		$this->handleRequest();
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
	 * Returns the Database object
	 * @return Database
	 */
	public static function getDB() {
		return self::$db;
	}
	
	/**
	 * Tries to authenticate the user by session or cookie.
	 * If no authentification parameters are provided,
	 * default to Guest
	 */
	protected function setUser() {
		
		session_name('userSession');
		session_set_cookie_params(0, str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));
		session_start();
		
		if (isset($_SESSION['userObject'])) {
			// request has a running session
			
			if (isset($_SESSION['cookieHash'])) {
				// check cookieHash for some additional security
				if (!empty($_COOKIE['cookieHash'])
					&& safeEquals($_SESSION['cookieHash'], $_COOKIE['cookieHash'])) {
					self::$user = unserialize($_SESSION['userObject']);
				} else {
					throw new FatalException('invalid session'); // TODO
				}
			} else {
				self::$user = unserialize($_SESSION['userObject']);
			}
			
		} elseif (isset($_COOKIE['userId']) && isset($_COOKIE['cookieHash'])) {
			// user sent cookie information
			$userData = $this->db->sendQuery(
				'SELECT * FROM `user` WHERE `userId` = ' // TODO replace *
				. intval($_COOKIE['userId'])
			)->fetch_assoc();
			
			if ($userData && safeEquals($userData['cookieHash'], $_COOKIE['cookieHash'])) {
				self::$user = new User($userData['userId'], $userData['userName'], $userData['email']);
				$_SESSION['userObject'] = serialize(self::$user);
				$_SESSION['cookieHash'] = $userData['cookieHash'];
			} else {
				throw new FatalException('invalid login'); // TODO
			}
			
		} else {
			// guest
			self::$user = new User(0, 'Guest' . rand(1000,9999) );
			$_SESSION['userObject'] = serialize(self::$user);
		}
	}
	
	/**
	 * Returns the User object
	 * @return User
	 */
	public static function getUser() {
		return self::$user;
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
	 * Initiates TemplateEngine.
	 */
	protected function setTemplateEngine() {
		self::$templateEngine = new TemplateEngine(self::$language);
	}
	
	/**
	 * Returns the TemplateEngine Object.
	 * @return 	TemplateEngine
	 */
	public static function getTemplateEngine() {
		return self::$templateEngine;
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
	 * Identifies the request and sets and calls
	 * the according controller.
	 */
	protected function handleRequest() {
		
		if (empty(self::$route)) {
			// no route at all, use default page
			self::$controller = new IndexController();
			
		} elseif (self::$route[0] === "ajax") {
			// ajax request route
			try {
				$controllerClass = $_POST['controller']."Controller";
				
				if (class_exists($controllerClass)
						&& is_subclass_of($controllerClass,'AjaxController')) {
					
					self::$controller = new $controllerClass();
					
				} else throw new RequestException($controllerClass." is not an AjaxController");
				
				self::$controller->handleAjaxRequest();
				return;
			} catch (RequestException $re) {
				// don't respond to bad ajax requests
				exit;
			}
				
		} else {
			// regular request route
			$controllerClass = self::$route[0]."Controller";
			
			if (class_exists($controllerClass)
					&& is_subclass_of($controllerClass,'RequestController')) {
				
				array_shift(self::$route);
				self::$controller = new $controllerClass();
				
			} elseif (Game::hashPregMatch(self::$route[0])) {
				// special feature: shorter urls for Game
				self::$controller = new GameController();
				
			} else throw new NotFoundException();
			
		}
		
		self::$controller->handleRequest(self::$route);
	}
	
	/**
	 * called automatically when an exception is thrown.
	 * @see set_exception_handler()
	 */
	public static function exceptionHandler(Exception $e) {
		if (method_exists($e,'show')) $e->show();
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
