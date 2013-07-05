<?php

/**
 * Represent a user in this System.
 * A user may be a Guest, a Player, a Spectator...
 * @author Philipp Miller
 */
class User extends GenericModel {
	
	/**
	 * Unique UserID
	 * @var integer
	 */
	protected $userId = 0;
	
	/**
	 * Users have names.
	 * @var string
	 */
	protected $userName = '';
	
	/**
	 * Registered users need an email adress
	 * @var string
	 */
	protected $email = '';
	
	/**
	 * Cookie hash is used for login via cookie.
	 * @var string
	 */
	protected $cookieHash = '';
	
	/**
	 * User's preferred language as string (e.g. 'en')
	 * @var string
	 */
	protected $language = '';
	
	/**
	 * Creates a User object using provided data
	 * or a Guest if none is provided.
	 * @param 	array<mixed> $userData
	 */
	public function __construct(array $userData = null) {
		if (is_null($userData)) {
			$this->userId = 0;
			$this->userName = 'Guest' . rand(1000,9999);
		} else {
			parent::__construct($userData);
		}
	}
	
	/**
	 * When treated as string, an instance of
	 * the User class will return the User's name.
	 * This method acts like an alias for getName().
	 * @return 	string
	 */
	public function __toString() {
		return $this->userName;
	}
	
	/**
	 * Getter for this User's id
	 * @return 	integer
	 */
	public function getId() {
		return $this->userId;
	}
	
	/**
	 * Getter for this User's name
	 * @return 	string
	 */
	public function getName() {
		return $this->userName;
	}
	
	/**
	 * Getter for this User's preferred language,
	 * returns false if no language is specified.
	 * @return 	string
	 */
	public function getLanguage() {
		// if (empty($this->language)) return false;
		// else return $this->language;
		return $this->language;
	}
	
	/**
	 * For use in URLs
	 * @return 	string
	 */
	public function getRoute() {
		$flatName = trim(preg_replace('#([^[:alnum:]]+)#','-',$this->userName), '-');
		return 'User/' . $this->userId . '-' . $flatName;
	}
	
	/**
	 * Creates a blowfish hash for password encryption.
	 * TODO create random salt
	 * @param 	string 	$password
	 * @param 	string 	$salt
	 * @return 	string
	 */
	public static function getPasswordHash($password, $salt) {
		$bcryptParams = '$2a$07$';
		return crypt(crypt($password, $bcryptParams.GLOBAL_SALT), $bcryptParams.$salt);
	}
	
	/**
	 * TODO
	 */
	public function guest() {
		return $this->userId == 0;
	}
	
	/**
	 * Generates a new cookieHash, saves it in database
	 * and sends it to client.
	 */
	public function regenerateCookieHash() {
		$cookieHash = Util::getRandomHash();
		if (Core::getDB()->sendQuery(
			"UPDATE `cc_user`
			 SET `cookieHash` = '" . $cookieHash . "' 
			 WHERE `userId` = " . $this->userId
		) !== true) throw new FatalException('could not reset cookieHash');
		Util::setCookie('cookieHash', $cookieHash);
		$this->cookieHash = $cookieHash;
	}
	
	/**
	 * Checks if the sent cookieHash matches this
	 * cookieHash.
	 * @return 	boolean
	 */
	public function checkCookieHash() {
		if (!empty($this->cookieHash)) {
			$cookieHash = Util::getCookie('cookieHash');
			if (!is_null($cookieHash)) {
				return Util::safeEquals($this->cookieHash, $cookieHash);
			}
			return false;
		}
		return true;
	}
}
