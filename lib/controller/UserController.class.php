<?php

/**
 * TODO
 */
class UserController implements RequestController {
	
	/**
	 * TODO
	 */
	public function handleRequest(array $route) {
		$param = array_shift($route);
		if (is_null($param)) {
			// own profile
			if (!Core::getUser()->guest()) {
				Core::getTemplateEngine()->showPage('userProfile');
				return;
			} else {
				throw new NotFoundException('you are a guest');
			}	
		}
		// user profile
		$userId = (explode('-', $param));
		if (0 < $userId = intval(array_shift($userId))) {
			// own profile
			if ($userId == Core::getUser()->getId()) {
				Core::getTemplateEngine()->showPage('userProfile');
				return;
			}
			
			$userData = Core::getDB()->sendQuery(
		 		'SELECT `userId`, `userName`, `email`
		 		 FROM `cc_user`
		 		 WHERE `userId` = ' . $userId
		 	)->fetch_assoc();
			
			if (!empty($userData)) {
				$user = new User($userData);
				Core::getTemplateEngine()->addVar('user', $user);
				Core::getTemplateEngine()->showPage('userProfile');
				return;
			}
			
			throw new NotFoundException('user doesn\'t exist');
		}
	
		// method
		switch ($param) {
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
			return false;
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
	
}
