<?php

/**
 * TODO
 */
class UserController implements RequestController {
	
	/**
	 * TODO
	 */
	public function handleRequest(array $route) {
		if (is_null($param = array_shift($route))) {
			// own profile
			$this->userProfile();
			return;
		}
		$userId = (explode('-', $param));
		if (is_numeric($userId[0])) {
			// specified profile
			$this->userProfile($userId[0]);
			return;
		}
		
		// method
		switch ($param) {
			case 'list':
				$this->userList();
				break;
			
			case 'login':
				if ($this->login()) {
					header('Location: ' . Util::url('User'));
					break;
				}
				throw new PermissionDeniedException('login failed');
				
			case 'logout':
				if ($this->logout()) {
					header('Location: ' . Util::url('Index'));
					break;
				}
				throw new PermissionDeniedException('logout failed');
				
			case 'register':
				//TODO
				throw new NotFoundException('not implemented');
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
		if (!Core::getUser()->guest()) {
			throw new PermissionDeniedException('already logged in');
		}
		if (isset($_POST['userName'])) {
			$userData = Core::getDB()->sendQuery(
		 		"SELECT `userId`, `userName`, `email`, `cookieHash`, `language`
		 		 FROM `cc_user`
		 		 WHERE `userName` = '" . Util::esc($_POST['userName']) . "'"
		 	)->fetch_assoc();
		
		 	if (!empty($userData)) { // TODO password check
		 		$user = new User($userData);
		 		$user->regenerateCookieHash();
		 		Util::setCookie('userId', $user->getId());
		 		$_SESSION['userObject'] = serialize($user);
		 		
		 	} else {
		 		// TODO user feedback
		 		return false;
		 	}
		} else {
			// TODO send login form
			throw new NotFoundException('loginForm not implemented');
		}
		return true;
	}
	
	/**
	 * TODO
	 */
	protected function logout() {
		if (Core::getUser()->guest()) {
			throw new PermissionDeniedException('already logged out');
		}
		setcookie('cc_userId', 0, 1, Util::cookiePath());
		
		setcookie('cc_cookieHash', '', 1, Util::cookiePath());
		// unset($_SESSION['cookieHash']);
		
		$guest = new User();
		$_SESSION['userObject'] = serialize($guest);
		return true;
	}
	
	// TODO
	protected function edit() {}
	protected function register() {}
	
	/**
	 * Shows a list of registered Users
	 */
	protected function userList() {
		$usersData = Core::getDB()->sendQuery(
			'SELECT `userId`,
			        `userName`
			 FROM `cc_user`
			 ORDER BY `userName`
			 LIMIT 30'
		);
		
		$users = array();
		while ($userData = $usersData->fetch_assoc()) {
			$users[] = new User($userData);
		}
		
		Core::getTemplateEngine()->addVar('users', $users);
		Core::getTemplateEngine()->showPage('userList');
	}
	
	/**
	 * Shows a User's profile page
	 */
	protected function userProfile($userId = 0) {
		if ($userId == 0) {
			// own profile
			if (Core::getUser()->guest()) {
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
			
			if (!empty($userData)) $user = new User($userData);
			else throw new NotFoundException('user does not exist');
		}
		Core::getTemplateEngine()->addVar('user', $user);
		Core::getTemplateEngine()->showPage('userProfile');
	}
	
}
