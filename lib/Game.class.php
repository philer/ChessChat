<?php
class Game {
	
	/**
	 * Unique game id
	 * @var int
	 */
	protected $id;
	
	/**
	 * This hash will be used in URLs for non-public games
	 * (compare YouTube or PastBin URLs)
	 * @var string
	 */
	protected $hash;
	
	/**
	 * Timestamp when the game was created
	 * @var int
	 */
	protected $timestamp;
	
	/**
	 * Opponent
	 * @var Player
	 */
	protected $otherPlayer;
	
	/**
	 * TODO
	 */
	public function __construct() {
		$this->id = 1;
		$this->otherPlayer = new Player("Larissa");
		$this->timestamp = time(); // maybe get timestamp from client's $_POST
	}
	
	/**
	 * Creates a hopefully unique hash for game identification.
	 * It containes digits and case sensitive letters
	 */
	protected function generateHash() {
		$hashString = $this->id
					+ $this->timestamp
					+ Core::user->getName()
					+ $this->otherPlayer->getName()
					+ GAME_SALT
					;
		$this->hash =
			substr( 						// take first 10 characters, should be enough
				str_replace('/','', 		// remove slashs (for PATH_INFO)
					base64_encode( 			// convert hash to case sensitive alphanum. string
						md5($hashString))) 	// hash the combined info string to raw binary
			0,10);
	}
}
