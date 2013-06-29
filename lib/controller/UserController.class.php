<?php

/**
 * TODO
 */
class UserController implements RequestController {
	
	/**
	 * TODO
	 */
	public function handleRequest(array $route) {
		$method = array_shift($route);
		if (!is_null($method)) {
			switch ($method) {
				case 'login':
					if ($this->login()) {
						header('Location: ' . Core::getTemplateEngine()->url('User'));
					} else {
						throw new PermissionDeniedException('login failed');
					}
					break;
					
				case 'logout':
					if ($this->logout()) {
						header('Location: ' . Core::getTemplateEngine()->url('Index'));
					} else {
						throw new PermissionDeniedException('logout failed');
					}
					break;
				
				case 'register':
					//TODO
					throw new NotFoundException('not implemented');
					break;
					
				default:
					throw new NotFoundException();
					break;
			}
		
		} elseif (!Core::getUser()->guest()) {
			Core::getTemplateEngine()->showPage('userProfile');
		} else {
			throw new NotFoundException('no route specified');
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
		// if (isset($_GET['userId'])) { //TEST
			$userData = Core::getDB()->sendQuery(
		 		"SELECT `userId`, `userName`, `email`, `cookieHash`, `language`
		 		 FROM `cc_user`
		 		 WHERE `userName` = '" . esc($_POST['userName']) . "'"
		 	)->fetch_assoc();
		 	if (!empty($userData)) { // TODO password check
		 		$user = new User(
		 			$userData['userId'],
		 			$userData['userName'],
		 			$userData['email'],
		 			$userData['language']);
		 		$_SESSION['userObject'] = serialize($user);
		 		setcookie('cc_userId', $userData['userId'], NOW+60*60*24*100, Util::cookiePath());
		 		
		 		$this->regenerateCookieHash($userData['userId']);
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
		unset($_SESSION['cookieHash']);
		
		$guest = new User(0, 'Guest' . rand(1000,9999) );
		$_SESSION['userObject'] = serialize($guest);
		return true;
	}
	
	// TODO
	protected function edit() {}
	protected function register() {}
	
	/**
	 * TODO
	 * @param 	integer 	$userId
	 */
	protected function regenerateCookieHash($userId) {
		$cookieHash = Util::getRandomHash();
		
		$success = Core::getDB()->sendQuery(
			"UPDATE `cc_user` SET `cookieHash` = '" . $cookieHash
			. "' WHERE `userId` = " . $userId
		);
		if ($success === true) {
			$_SESSION['cookieHash'] = $cookieHash;
			setcookie('cc_cookieHash', $cookieHash, NOW+60*60*24*100, Util::cookiePath());
		} else {
			throw new FatalException('could not reset cookieHash');
		}
		

	}
}
