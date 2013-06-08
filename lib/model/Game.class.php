<?php

/**
 * Represents a Game
 * @author Philipp Miller
 */
class Game {
	
	/**
	 * Unique game id
	 * @var integer
	 */
	protected $id = 0;
	
	/**
	 * This hash will be used in URLs for non-public games
	 * (compare YouTube or PastBin URLs)
	 * @var string
	 */
	protected $hash = '';
	
	/**
	 * Timestamp when the game was created
	 * @var integer
	 */
	protected $timestamp = 0;
	
	/**
	 * Player one's id
	 * @var integer
	 */
	protected $whitePlayerId = 0;
	
	/**
	 * Player two's id
	 * @var integer
	 */
	protected $blackPlayerId = 0;
	
	/**
	 * Player one
	 * @var Player
	 */
	protected $whitePlayer = null;
	
	/**
	 * Player two
	 * @var Player
	 */
	protected $blackPlayer = null;
	
	/**
	 * Every Game has a Chat.
	 * @var Chat
	 */
	protected $chat = null;
	
	/**
	 * Chessboard
	 * @var TODO
	 */
	protected $board;
	
	/**
	 * Chessboard represented as a string
	 * @var string
	 */
	protected $boardString = "";
	
	/**
	 * String representation of the board at the start of a game
	 * @var string
	 */
	const DEFAULT_BOARD_STRING =
	"Ra1Nb1Bc1Qd1Kd1Bc1Nb1Ra1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7ra8nb8bc8qd8kd8bc8nb8ra8";
	
	/**
	 * TODO
	 */
	public function __construct() {}

	/**
	 * Creates a hopefully unique hash for game identification.
	 * It containes digits and case sensitive letters
	 */
	protected function generateHash() {
		// put anything useful in the gamehash.
		// it doesn't have to be cryptographically safe,
		// just don't stumble over it.
		$hashString = $this->id
					+ $this->timestamp
					+ Core::$user->getName()
					+ $this->otherPlayer->getName()
					+ GAME_SALT;
		// generate hash:
		// generate md5, base64 it,
		// remove bad characters, then take only first few characters
		$this->hash = substr(
						str_replace(
							array('/','+','='), '',
							base64_encode(
								md5($hashString))),
						0, GAME_HASH_LENGTH);
	}
	
	/**
	 * Checks if provided string may be a valid game hash
	 * (Does not check if it exists!)
	 * @param 	string 		$string
	 * @return 	integer 	1, 0 or FALSE
	 */
	public static function hashPregMatch($string) {
		return preg_match(self::getHashPattern(), $string);
	}
	
	/**
	 * Returns this game's hash.
	 * @return 	string
	 */
	public function getHash() {
		return $this->hash;
	}
	
	/**
	 * Returns the regex pattern for a valid
	 * game hash string.
	 * Unfortunately this can not be implemented as a const.
	 * @return 	string
	 */
	public static function getHashPattern() {
		return "#^[[:alnum:]]{".GAME_HASH_LENGTH."}$#i";
	}
	
	// TODO
	protected function move($move) {}
	protected function validateMove($board, $move) {}
	
}
