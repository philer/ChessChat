<?php

/**
 * Represent a user in this System.
 * A user may be a Guest, a Player, a Spectator...
 * @author Philipp Miller
 */
class User {
	
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
	 * User's preferred language as string (e.g. 'en')
	 * @var string
	 */
	protected $language = '';
	
	/**
	 * TODO
	 */
	public function __construct($userId, $userName, $email = '', $language = '') {
		$this->userId = $userId;
		$this->userName = $userName;
		if (!empty($email))    $this->email    = $email;
		if (!empty($language)) $this->language = $language;
		// TODO remove password + hash?
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
}
