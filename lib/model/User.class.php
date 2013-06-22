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
	protected $id = 0;
	
	/**
	 * Users have names.
	 * @var string
	 */
	protected $name = "";
	
	/**
	 * Registered users need an email adress
	 * @var string
	 */
	protected $email = "";
	
	/**
	 * Password for registered users
	 * @var string
	 */
	protected $password = "";
	
	/**
	 * unique PlayerHash for cookie identification
	 * @var string
	 */
	protected $cookieHash = "";
	
	/**
	 * TODO
	 */
	public function __construct($id, $name = "", $password = "", $hash = "") {
		$this->id = $id;
		if (!empty($name)) $this->name = $name;
	}
	
	/**
	 * When treated as string, an instance of
	 * the User class will return the User's name.
	 * This method acts like an alias for getName().
	 * @return 	string
	 */
	public function __toString() {
		return $this->name;
	}
	
	/**
	 * Getter for this User's id
	 * @return 	integer
	 */
	public function getId() {
		return $this->id;
	}	
	
	/**
	 * Getter for this User's name
	 * @return 	string
	 */
	public function getName() {
		return $this->name;
	}
}
