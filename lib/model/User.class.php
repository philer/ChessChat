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
	protected $id;
	
	/**
	 * Users have names.
	 * @var string
	 */
	protected $name;
	
	/**
	 * Registered users need an email adress
	 * @var string
	 */
	protected $email;
	
	/**
	 * Password for registered users
	 * @var string
	 */
	protected $password;
	
	/**
	 * unique PlayerHash for cookie identification
	 * @var string
	 */
	protected $hash;
	
	/**
	 * TODO
	 */
	public function __construct($id, $name = "", $password = "", $hash = "") {
		$this->id = $id;
		if (!empty($name)) $this->name = $name;
	}
	
	public function __toString() {
		return $this->name;
	}
}
