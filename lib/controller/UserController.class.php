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
					// TODO remove exceptions
					if ($this->login()) {
						throw new NotFoundException('successfully logged in');
					} else {
						throw new PermissionDeniedException('loggin failed');
					}
					break;
					
				case 'logout':
					// TODO remove exceptions
					if ($this->logout()) {
						throw new NotFoundException('successfully logged out');
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
		}
		throw new NotFoundException('no route specified');
	}
	
	/**
	 * TODO
	 */
	protected function login() {
		if (!Core::getUser()->guest()) {
			throw new PermissionDeniedException('already logged in');
		}
		if (isset($_GET['userId'])) { //TEST
			$userData = Core::getDB()->sendQuery(
		 		'SELECT `userId`, `userName`, `email`, `cookieHash`, `language`
		 		 FROM `cc_user`
		 		 WHERE `userId` = ' . intval($_GET['userId']) // TEST
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
			// TODO user feedback
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
	
	protected function edit() {}
	protected function register() {}
	
	
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
