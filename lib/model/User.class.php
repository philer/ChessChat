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
	 * Amount of games this user is part of
	 * @var integer
	 */
	protected $gameCount = 0;
	
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
		return $this->language;
	}
	
	public function getGameCount() {
		return $this->gameCount;
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
	 * Checks if this user is a guest or a registered user
	 * @return  boolean
	 */
	public function isGuest() {
		return $this->userId === 0;
	}
	
	/**
	 * Checks if this user is the requesting user
	 * @return boolean
	 */
	public function isSelf() {
		return $this->userId === Core::getUser()->getId();
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
	
	/**
	 * Returns a pattern matching valid usernames
	 * @param  string $delimiter defaults to '#'
	 * @return string pattern
	 */
	public static function getUserNamePattern($delimiter = '#') {
		return $delimiter . '^'
		     . (USERNAME_FORCE_ASCII ? '[\x20-\x7E]' : '.')
		     . '{' . USERNAME_MIN_LENGTH . ',' . USERNAME_MAX_LENGTH . '}$'
		     . $delimiter;
	}
	
	/**
	 * Checks if $userName meets the required userName regulations.
	 * @param  string $userName
	 * @return boolean
	 */
	public static function validUserName($userName) {
		// return strlen($userName) >= USERNAME_MIN_LENGTH
		//     && strlen($userName) <= USERNAME_MAX_LENGTH;
		return preg_match(self::getUserNamePattern(), $userName) === 1;
	}
	
	/**
	 * Returns a pattern matching valid email addresses
	 * @param  string $delimiter defaults to '#'
	 * @return string pattern
	 */
	public static function getEmailPattern($delimiter = '#') {
		return $delimiter . '^\S+@[[:word:]0-9_.-]+\.[[:word:]]+$' . $delimiter;
	}
	
	/**
	 * Checks if $str is a valid email address.
	 * TODO better pattern maybe
	 * @param  string	$str
	 * @return boolean
	 */
	public static function validEmail($email) {
		return preg_match(self::getEmailPattern(), $email) === 1;
	}
	
	/**
	 * Returns a pattern matching valid password
	 * @param  string $delimiter defaults to '#'
	 * @return string pattern
	 */
	public static function getPasswordPattern($delimiter = '#') {
		return $delimiter . '^.{' . PASSWORD_MIN_LENGTH . ',}$' . $delimiter;
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
