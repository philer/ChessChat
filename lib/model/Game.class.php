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
	 * Chessboard
	 * @see chessboard.tpl.php for an example
	 * @var array<array>
	 */
	protected $board;
	
	/**
	 * Chessboard represented as a string for easy transmission and storage
	 * Conventions:
	 * - 3 characters per piece. (3*32 = 96 total)
	 * - first character: piece type, capital for white
	 * - second character: file (column)
	 *		+ capital for king means no castling yet
	 * 		+ capital for pawn means he just did a double step, which is
	 * 		  relevant for en passant (update it after next move!)
	 * - third character: rank (row)
	 * - file 'x' for dead white pieces, file 'y' for dead black pieces
	 * @var string
	 */
	protected $boardString = "";
	
	/**
	 * String representation of the board at the start of a game.
	 * @var string
	 */
	const DEFAULT_BOARD_STRING =
	"Ra1Nb1Bc1Qd1Kd1Bc1Nb1Ra1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7ra8nb8bc8qd8kd8bc8nb8ra8";
	
	/**
	 * Every Game needs a GameController as a parent
	 * @var GameController
	 */
	protected $gameController = null;
	
	/**
	 * Initializes a Game with a
	 * GameController as a parent.
	 * @param 	GameController 	$gameController
	 */
	public function __construct(GameController $gameController) {
		$this->gameController = $gameController;
		// TODO
	}
	
	/**
	 * TODO
	 */
	public static function create() {
		// TODO
		// return new self(self::DEFAULT_BOARD_STRING);
	}
	
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
	public function move($move) {
		if ($this->validateMove($move)) {
			// TODO
			return true;
		} else return "invalidMessage";
	}
	
	//TODO
	public function validateMove($move) {}
	public static function boardFromString($boardStr) {}
	public static function boardToString($board) {}
	
	/**
	 * Checks if given string may be a move
	 * pattern supported by this system.
	 * DOES NOT validate or execute the move.
	 * @param 	string 	$str
	 * @return 	boolean
	 */
	public static function matchMovePattern($str) {
		$square = '([a-hA-H][1-8]|[1-8][a-hA-H])';
		$separator = '[_ -]?';
		return preg_match('@^'.$square.$separator.$square.'$@', $str);	
		// $piece = '[pkqnbrPKQNBR]'; // TODO language support maybe?
		// return preg_match('@^'.$square.$separator.$square.'|'.$piece.$square.'$@', $str);
	}
}
