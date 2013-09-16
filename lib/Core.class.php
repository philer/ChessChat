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
	 * Calls all core init methods
	 */
	public function __construct() {
		
		$this->setDB();
		$this->setUser();
		$this->setLanguage();
		$this->setTemplateEngine();
		
		$this->handleRequest();
	}
	
	/**
	 * Reads database config and creates the database object
	 */
	protected function setDB() {
		$dbHost = $dbUser = $dbPass = $dbName = '';
		require_once(ROOT_DIR . 'config/db.conf.php');
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
		session_name(COOKIE_PREFIX . 'sid');
		session_set_cookie_params(0, Util::getRequestPath());
		session_start();
		
		// existing session
		if (isset($_SESSION['userObject'])) {
			$user = unserialize($_SESSION['userObject']);
			if ($user->checkCookieHash()) {
				self::$user = $user;
				return;
			}
		}
		
		// no session but cookie
		elseif (!is_null($userId = Util::getCookie('userId'))) {
			$userData = self::$db->sendQuery(
				'SELECT userId, userName, email, cookieHash, language
				 FROM cc_user
				 WHERE userId = ' . intval($userId)
			)->fetch_assoc();
			
			if (!empty($userData)) {
				$user = new User($userData);
				if ($user->checkCookieHash()) {
					self::$user = $user;
					$_SESSION['userObject'] = serialize(self::$user);
					return;
				} else {
					sleep(INVALID_LOGIN_WAIT);
				}
			}
		}
		
		// guest
		self::$user = new User();
		$_SESSION['userObject'] = serialize(self::$user);
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
	 * Returns the Language object
	 * @return 	Language
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
	 * Identifies the request and sets and calls
	 * the according controller.
	 */
	protected function handleRequest() {
		$route = Util::getRoute();
		
		if (empty($route)) {
			// no route at all, use default page
			$controller = new IndexController();
			
		} elseif ($route[0] === "ajax") {
			// ajax request route
			try {
				$controllerClass = $_POST['controller'];
				if ($controllerClass == 'TemplateEngine') {
					AjaxUtil::queueReply('tpl',
							self::$templateEngine->fetch($_POST['tpl'])
						);
				} elseif (class_exists($controllerClass)
						&& is_subclass_of($controllerClass, 'AjaxController')) {
					$controller = new $controllerClass();
					$controller->handleAjaxRequest();
				} else {
					throw new RequestException("'" . $controllerClass . "' is not an AjaxController");
				}
				AjaxUtil::sendReply();
				return;
			} catch (RequestException $re) {
				// don't respond to bad ajax requests
				if (DEBUG_MODE) throw $re;
				exit;
			}
			
		} else {
			// regular request route
			$controllerClass = $route[0] . 'Controller';
			if (class_exists($controllerClass)
					&& is_subclass_of($controllerClass, 'RequestController')) {
				array_shift($route);
				$controller = new $controllerClass();
				
			} elseif (Game::hashPatternMatch($route[0])) {
				// special feature: shorter urls for Games
				$controller = new GameController();
			}
			
		}
		self::getTemplateEngine()->registerDefaultScripts();
		if (!isset($controller)) throw new NotFoundException('no controller specified');
		$controller->handleRequest($route);
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
