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
				}
				break;
				
			default:
				throw new NotFoundException('method doesn\'t exist');
				break;
		}
	}
	
	/**
	 * TODO
	 */
	protected function login() {
		if (!Core::getUser()->isGuest()) {
			throw new PermissionDeniedException('already logged in');
		}
		if (!empty($_POST['userName'])) {
			$userData = Core::getDB()->sendQuery(
		 		"SELECT userId, userName, email, cookieHash, language
		 		 FROM cc_user
		 		 WHERE userName = '" . Util::esc($_POST['userName']) . "'"
		 	)->fetch_assoc();
			
		 	if (!empty($userData)) { // TODO password check
		 		$user = new User($userData);
		 		$user->regenerateCookieHash();
		 		Util::setCookie('userId', $user->getId());
		 		$_SESSION['userObject'] = serialize($user);
		 		
		 		header('Location: ' . Util::url('User'));
		 		return true;
		 	}
		 	
		 	Core::getTemplateEngine()->addVar('errorMessage', 'bad input');
		 	Core::getTemplateEngine()->addVar('invalid', array('userName'));
		}
		return false;
	}
	
	/**
	 * TODO
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
		// TODO
		return false;
	}
		
	// TODO
	protected function edit() {}
	
	/**
	 * Prepares data for a user list to be
	 * used in templates.
	 */
	protected function prepareUserList() {
		$usersData = Core::getDB()->sendQuery(
			'SELECT userId,
			        userName
			 FROM cc_user
			 ORDER BY userName
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
		if ($userId == 0) {
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
	
}
