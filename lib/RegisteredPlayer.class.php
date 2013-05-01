<?php
class RegisteredPlayer {
	
	/**
	 * Password for registered Players
	 * @var String
	 */
	protected $password;
	
	/**
	 * Registered Players need an email adress
	 * @var String
	 */
	protected $email;
	
	
	public function __construct($id, $name = '', $hash = '') {
		
	}
}
