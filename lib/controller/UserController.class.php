<?php

/**
 * Takes care of all User related actions
 * @author  Philipp Miller
 */
class UserController extends AbstractRequestController {
	
	/**
	 * Does what needs to be done for this request.
	 */
	public function handleRequest(array $route) {
		if (is_null($param = array_shift($route))) {
			// own profile
			$this->prepareUserProfile();
			Core::getTemplateEngine()->showPage('userProfile', $this);
			return;
		}
		$userId = (explode('-', $param));
		if (is_numeric($userId[0])) {
			// specified profile
			$this->prepareUserProfile($userId[0]);
			Core::getTemplateEngine()->showPage('userProfile', $this);
			return;
		}
		
		// method
		$this->route .= $param;
		switch ($param) {
			case 'list':
				$this->prepareUserList();
				Core::getTemplateEngine()->showPage('userList', $this);
				break;
			
			case 'login':
				if (!$this->login()) {
					Core::getTemplateEngine()->showPage('loginForm', $this);
				}
				break;
				
			case 'logout':
				$this->logout();
				break;
				
			case 'register':
				if (!$this->register()) {
					Core::getTemplateEngine()->showPage('registerForm', $this);
				} else {
					if (!$this->login()) {
						Core::getTemplateEngine()->showPage('loginForm', $this);
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
		if (!empty($_POST)) {
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
				} elseif (!Util::checkPassword($_POST['password'],$userData['password'])) {
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
		}
		$this->pageTitle = Core::getLanguage()->getLanguageItem('user.login');
		return false;
		
	}
	
	/**
	 * Handles user logout.
	 */
	protected function logout() {
		if (Core::getUser()->isGuest()) {
			throw new PermissionDeniedException('already logged out');
		}
		$guest = new User();
		$_SESSION['userObject'] = serialize($guest);
		Util::deleteCookie('userId');
		Util::deleteCookie('cookieHash');
		
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
		if (!empty($_POST)) {
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
					if ($key != "password" && $key != "passwordConfirm") {
						$data[$key] = trim($_POST[$key]);
					} else {
						$data[$key] = $_POST[$key];
					}
				}
			}
			if (empty($invalid['userName'])) {
				if (!User::validUserName($data['userName'])) {
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
			if (empty($invalid['email']) && !User::validEmail($data['email'])) {
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
			
			if (empty($invalid['password']) && !User::validPassword($data['password'])) {
				$invalid['password'] = 'form.invalid.password.insecure';
			}
			if (empty($invalid['password']) && empty($invalid['passwordConfirm'])
				&& $data['password'] !== $data['passwordConfirm']) {
				$invalid['passwordConfirm'] = 'form.invalid.passwordConfirm';
			}
			
			if (empty($invalid)) {
				// save
				// TODO encrypt password!
				Core::getDB()->sendQuery("
					INSERT INTO cc_user (userName, email, password, language)
					VALUES ('" . Util::esc($data['userName']) . "',
					        '" . Util::esc($data['email'])    . "',
					        '" . Util::encrypt($data['password']) . "',
					        '" . Util::esc($data['language']) . "')
					");
				return true;
			}
			Core::getTemplateEngine()->addVar('errorMessage', 'form.invalid');
			Core::getTemplateEngine()->addVar('invalid', $invalid);
		}
		$this->pageTitle = Core::getLanguage()->getLanguageItem('user.register');
		return false;
		
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
		$this->pageTitle = Core::getLanguage()->getLanguageItem('user.list');
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
				'SELECT userId, userName, email
				 FROM cc_user
				 WHERE userId = ' . intval($userId)
			)->fetch_assoc();
			if (empty($userData)) throw new NotFoundException('user does not exist');
			
			$user = new User($userData);
		}
		Core::getTemplateEngine()->addVar('user', $user);
		$this->pageTitle = (string) $user;
		$this->route     = $user->getRoute();
		
		$gameController = new GameController();
		$gameController->prepareGameList($user->getId());
	}
	
}
