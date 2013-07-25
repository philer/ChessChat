<?php

/**
 * Takes care of all User related actions
 * @author  Philipp Miller
 */
class UserController implements RequestController {
	
	/**
	 * Does what needs to be done for this request.
	 */
	public function handleRequest(array $route) {
		if (is_null($param = array_shift($route))) {
			// own profile
			$this->prepareUserProfile();
			Core::getTemplateEngine()->showPage('userProfile');
			return;
		}
		$userId = (explode('-', $param));
		if (is_numeric($userId[0])) {
			// specified profile
			$this->prepareUserProfile($userId[0]);
			Core::getTemplateEngine()->showPage('userProfile');
			return;
		}
		
		// method
		switch ($param) {
			case 'list':
				$this->prepareUserList();
				Core::getTemplateEngine()->showPage('userList');
				break;
			
			case 'login':
				if (!$this->login()) {
					Core::getTemplateEngine()->showPage('loginForm');
				}
				break;
				
			case 'logout':
				$this->logout();
				break;
				
			case 'register':
				if (!$this->register()) {
					Core::getTemplateEngine()->showPage('registerForm');
				} else {
					if (!$this->login()) {
						Core::getTemplateEngine()->showPage('loginForm');
					}
				}
				break;
				
			default:
				throw new NotFoundException('method doesn\'t exist');
				break;
		}
	}
	
	/**
	 * Handles user login.
	 */
	protected function login() {
		if (!Core::getUser()->isGuest()) {
			throw new PermissionDeniedException('already logged in');
		}
		if (empty($_POST)) {
			// empty form requested
			return false;
		}
		
		// validation
		$invalid = array();
		if (empty($_POST['userName'])) {
			$invalid['userName'] = 'form.invalid.missing';
		}
		if (empty($_POST['password'])) {
			$invalid['password'] = 'form.invalid.missing';
		}
		
		if (empty($invalid)) {
			$userData = Core::getDB()->sendQuery(
				"SELECT userId, userName, email, password, cookieHash, language
				 FROM cc_user
				 WHERE userName = '" . Util::esc($_POST['userName']) . "'"
			)->fetch_assoc();

			if (empty($userData)) {
				$invalid['userName'] = 'form.invalid.userName.nonexistant';
			} elseif (!Util::safeEquals($_POST['password'], $userData['password'])) {
				// TODO use password encryption
				$invalid['password'] = 'form.invalid.password';
			} else {
				$user = new User($userData);
				$user->regenerateCookieHash();
				Util::setCookie('userId', $user->getId());
				$_SESSION['userObject'] = serialize($user);
				
				header('Location: ' . Util::url('User'));
				return true;
			}
		}
		Core::getTemplateEngine()->addVar('errorMessage', 'form.invalid');
		Core::getTemplateEngine()->addVar('invalid', $invalid);
		sleep(INVALID_LOGIN_WAIT);
		return false;
		
	}
	
	/**
	 * Handles user logout.
	 */
	protected function logout() {
		if (Core::getUser()->isGuest()) {
			throw new PermissionDeniedException('already logged out');
		}
		setcookie('cc_userId', 0, 1, Util::cookiePath());
		
		setcookie('cc_cookieHash', '', 1, Util::cookiePath());
		// unset($_SESSION['cookieHash']);
		
		$guest = new User();
		$_SESSION['userObject'] = serialize($guest);
		
		header('Location: ' . Util::url('Index'));
	}
	
	/**
	 * Creates a new User from provided form data.
	 * If creation failes due to incorrect user input
	 * assigns template variables for form values.
	 * @return boolean creation success
	 */
	protected function register() {
		if (!Core::getUser()->isGuest()) {
			throw new PermissionDeniedException('already logged in');
		}
		if (empty($_POST)) {
			// empty form requested
			return false;
		}
		
		$data = array('userName'        => null,
			          'email'           => null,
			          'emailConfirm'    => null,
			          'password'        => null,
			          'passwordConfirm' => null,
			          'language'        => null);
		
		// validation
		$invalid = array();
		foreach ($data as $key => $x) {
			if (empty($_POST[$key])) {
				$invalid[$key] = 'form.invalid.missing';
			} else {
				$data[$key] = trim($_POST[$key]);
			}
		}
		if (empty($invalid['userName'])) {
			if (!self::validUserName($data['userName'])) {
				$invalid['userName'] = 'form.invalid.userName';
			} else {
				$userCount = Core::getDB()->sendQuery(
					"SELECT COUNT(*) as usern
					 FROM cc_user
					 WHERE userName = '" . Util::esc($data['userName']) . "'"
				)->fetch_assoc();
				if ($userCount['usern'] != 0) {
					$invalid[$key] = 'form.invalid.userName.used';
				}
			}
		}
		if (empty($invalid['email']) && !self::validEmail($data['email'])) {
			$invalid['email'] = 'form.invalid.email';
		} else {
			$userCount = Core::getDB()->sendQuery(
				"SELECT COUNT(*) as usern
				 FROM cc_user
				 WHERE email = '" . Util::esc($data['email']) . "'"
			)->fetch_assoc();
			if ($userCount['usern'] != 0) {
				$invalid[$key] = 'form.invalid.email.used';
			}
		}
		if (empty($invalid['email']) && empty($invalid['emailConfirm'])
			&& $data['email'] !== $data['emailConfirm']) {
			$invalid['emailConfirm'] = 'form.invalid.emailConfirm';
		}
		
		if (empty($invalid['password']) && !self::validPassword($data['password'])) {
			$invalid['password'] = 'form.invalid.password.insecure';
		}
		if (empty($invalid['password']) && empty($invalid['passwordConfirm'])
			&& $data['password'] !== $data['passwordConfirm']) {
			$invalid['passwordConfirm'] = 'form.invalid.passwordConfirm';
		}
		if (!empty($invalid)) {
			Core::getTemplateEngine()->addVar('errorMessage', 'form.invalid');
			Core::getTemplateEngine()->addVar('invalid', $invalid);
			return false;
		}
		
		// save
		// TODO encrypt password!
		Core::getDB()->sendQuery("
			INSERT INTO cc_user (userName, email, password, language)
			VALUES ('" . Util::esc($data['userName']) . "',
			        '" . Util::esc($data['email'])    . "',
			        '" . Util::esc($data['password']) . "',
			        '" . Util::esc($data['language']) . "')
			");
		return true;
	}
	
	/**
	 * Prepares data for a user list to be
	 * used in templates.
	 */
	protected function prepareUserList() {
		$usersData = Core::getDB()->sendQuery(
			'SELECT userId,
			        userName,
			        (SELECT COUNT(*)
			         FROM cc_game G
			         WHERE U.userId = G.whitePlayerId
			            OR U.userId = G.blackPlayerId) as gameCount
			 FROM cc_user U
			 ORDER BY gameCount DESC, userName ASC
			 LIMIT 30'
		);
		
		$users = array();
		while ($userData = $usersData->fetch_assoc()) {
			$users[] = new User($userData);
		}
		Core::getTemplateEngine()->addVar('users', $users);
	}
	
	/**
	 * Prepares data for a user profile to be
	 * used in templates. If no $userId > 0 is
	 * specified will default to the current user
	 * or throw an error if this is a guest.
	 * @param  integer $userId
	 */
	protected function prepareUserProfile($userId = 0) {
		if ($userId == 0 || $userId == Core::getUser()->getId()) {
			// own profile
			if (Core::getUser()->isGuest()) {
				throw new NotFoundException('you are a guest');
			} else {
				$user = Core::getUser();
			}
		} else {
			$userData = Core::getDB()->sendQuery(
		 		'SELECT `userId`, `userName`, `email`
		 		 FROM `cc_user`
		 		 WHERE `userId` = ' . intval($userId)
		 	)->fetch_assoc();
			if (empty($userData)) throw new NotFoundException('user does not exist');
			
			$user = new User($userData);
		}
		Core::getTemplateEngine()->addVar('user', $user);

		$gameController = new GameController();
		$gameController->prepareGameList($user->getId());
	}
	
	/**
	 * Checks if $userName meets the required userName regulations.
	 * @param  string $userName
	 * @return boolean
	 */
	public static function validUserName($userName) {
		return strlen($userName) >= USERNAME_MIN_LENGTH
		    && strlen($userName) <= USERNAME_MAX_LENGTH;
	}
	
	/**
	 * Checks if $str is a valid e-mail address.
	 * TODO better pattern maybe
	 * @param  string	$str
	 * @return boolean
	 */
	public static function validEmail($email) {
		$pattern = '#^\S+@[[:word:]0-9_.-]+\.[[:word:]]+$#';
		return preg_match($pattern, $email) === 1;
	}
	
	/**
	 * Checks if $pw meets the required password criteria.
	 * @param  string	$pw
	 * @return boolean
	 */
	public static function validPassword($pw) {
		return strlen($pw) >= PASSWORD_MIN_LENGTH;
		// if (!( strlen($pw) >= PASSWORD_MIN_LENGTH
		//     && strlen($pw) <= PASSWORD_MAX_LENGTH
		//     && (!PASSWORD_REQUIRE_LOWERCASE || preg_match('#[a-z]+#', $pw))
		//     && (!PASSWORD_REQUIRE_UPPERCASE || preg_match('#[A-Z]+#', $pw))
		//     && (!PASSWORD_REQUIRE_NUMERIC   || preg_match('#[0-9]+#', $pw))
		//    )) {
		// 	return false;
		// }
		// if ('' !== $chars = PASSWORD_REQUIRE_SPECIALCHARS) {
		// 	for ($i=0 ; $i<strlen($chars) ; $i++) {
		// 		if (strpos($pw, $chars[$i]) !== false) {
		// 			return true;
		// 		}
		// 	}
		// 	return false;
		// }
		// return true;
	}
}
