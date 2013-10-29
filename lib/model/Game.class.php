<?php

/**
 * Represents a Game
 * @author Philipp Miller, Larissa Hammerstein
 */
class Game extends DatabaseModel {
	
	/**
	 * Unique game id
	 * @var integer
	 */
	protected $gameId = 0;
	
	/**
	 * This hash will be used in URLs for non-public games
	 * (compare YouTube or PastBin URLs)
	 * @var string
	 */
	protected $gameHash = '';
	
	/**
	 * Player one
	 * @var integer
	 */
	protected $whitePlayer = null;
	
	/**
	 * Player two
	 * @var integer
	 */
	protected $blackPlayer = null;
	
	/**
	 * Last update for this game
	 * @var integer ?
	 */
	protected $lastUpdate = 0;
	
	/**
	 * Chessboard
	 * @see chessboard.tpl.php for an example
	 * @var array<array>
	 */
	public $board = array();
	
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
	 * - files 'v' and 'w' for dead white pieces, files 'x' and 'y' for dead black pieces
	 * TODO(- first chesspiece indicates who's turn it is (redundant with status))
	 * @var string
	 */
	protected $boardString = '';
	
	/**
	 * String representation of the board at the start of a game.
	 * @var string
	 */
	const DEFAULT_BOARD_STRING =
	'RA1Nb1Bc1Qd1KD1Bc1Nb1RA1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kD8bc8nb8rA8';
	
	/**
	 * Game status as a short integer value
	 * 
	 * Status constants can be combined to indicate
	 * who's turn/checkmate/stalemate/... it is.
	 * Examples:
	 * $this->status = Game::STATUS_CHECKMATE & Game::STATUS_WHITES_TURN;
	 * 	indicates, that black has won by checkmate.
	 * $this->status = Game::STATUS_DRAW & Game::STATUS_BLACKS_TURN;
	 * 	indicates, that black has offered a draw which was accepted by white.
	 * 
	 * 0 through 5 for ongoing games
	 * 6 through 9 for won games
	 * 10 through 15 for draws
	 * @see Game constants
	 * @var integer
	 */
	protected $status = 0;
	
	/**
	 * Game status for white player's turn
	 * @var integer 0b0
	 */
	const STATUS_WHITES_TURN = 0;
	
	/**
	 * Game status for black player's turn
	 * @var integer 0b1
	 */
	const STATUS_BLACKS_TURN = 1;
	
	/**
	 * Game status for check
	 * @var integer 0b10
	 */
	const STATUS_CHECK = 2;
	
	/**
	 * Game status for a draw offering that
	 * has not yet been accepted.
	 * @var integer 0b100
	 */
	const STATUS_DRAW_OFFERED = 4;
	
	/**
	 * Game status for last player's resignation
	 * @var integer 0b110
	 */
	const STATUS_RESIGNED = 6;
	
	/**
	 * Game status for laste player's checkmate
	 * @var integer 0b1000
	 */
	const STATUS_CHECKMATE = 8;
	
	/**
	 * Game status for last player's accepted draw
	 * @var integer 0b1010
	 */
	const STATUS_DRAW = 10;
	
	/**
	 * Game status for last player's achieved stalemate
	 * @var integer 0b1100
	 */
	const STATUS_STALEMATE = 12;
	
	/**
	 * Game status for draw by threefold repetition rule
	 * @var integer 0b1110
	 */
	const STATUS_THREEFOLD_REPETITION = 14;
	
	/**
	 * Game status for draw by fifty moves rule
	 * @var integer 0b1111
	 */
	const STATUS_FIFTY_MOVES = 15;
	
	/**
	 * Creates a Game object using provided data
	 * or a new game if none is provided.
	 * @param 	array<mixed> $gameData
	 */
	public function __construct(array $gameData = array()) {
		if (isset($gameData['whitePlayerId'])) {
			// assume both white's and black's Id and name are set
			$this->whitePlayer = new User(array(
				'userId'   => $gameData['whitePlayerId'],
				'userName' => $gameData['whitePlayerName']
				));
			$this->blackPlayer = new User(array(
				'userId'   => $gameData['blackPlayerId'],
				'userName' => $gameData['blackPlayerName']
				));
		
		}
		// don't need this anymore
		unset($gameData['whitePlayerId'], $gameData['whitePlayerName'], $gameData['blackPlayerId'], $gameData['blackPlayerName']);
		
		if (empty($gameData)) {
			// TODO create database entry, get gameId
			$this->gameHash = $this->generateHash();
			$this->boardString = self::DEFAULT_BOARD_STRING;
			// $this->board = $this->boardFromString($this->boardString);
			$this->status = self::STATUS_WHITES_TURN;
		} else {
			parent::__construct($gameData);
		}
	}
	
	/**
	 * Returns this game's gameId
	 * @return 	integer
	 */
	public function getId() {
		return $this->gameId;
	}
	
	/**
	 * Creates a hopefully unique hash for game identification.
	 * It containes digits and case sensitive letters
	 */
	protected function generateHash() {
		// put anything useful in the gamehash.
		// it doesn't have to be cryptographically safe,
		// just don't stumble over it.
		$hashString = $this->gameId
					. NOW
					. GAME_SALT
					. $this->whitePlayer
					. $this->blackPlayer;
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
	public static function hashPatternMatch($string) {
		return preg_match(
			'#^[[:alnum:]]{' . GAME_HASH_LENGTH . '}$#i',
			$string
		);
	}
	
	/**
	 * Returns this game's hash.
	 * @return 	string
	 */
	public function getHash() {
		return $this->gameHash;
	}
	
	/**
	 * Checks and executes a given Move.
	 * @param Move $move
	 */
	public function move(Move &$move) {
		if ($move->valid) {
			// TODO execute/save move, update status
		}
	}
	
	/**
	 * Chessboard represented as a string for easy transmission and storage
	 * @see  Game::$boardString
	 * @see  Game::boardToString()
	 * @param  string $boardStr
	 * @return array<ChessPiece>
	 */
	public static function boardFromString($boardStr) { 
		$board = array();
		for ( $file='a' ; $file<='h' ; $file++ ) {
			$board[$file] = new Array()
			for ( $rank=1; $rank<=8 ; $rank++ ) {
				$board[$file][$rank] = null;
			}
		}
		for ( $prison='v' ; $prison<='y' ; $prison++) $board[$prison] = array();
		
		for ( $cp=0 ; $cp<32 ; $cp+=3 ) {
			switch(strtolower($boardStr[$cp])) {
				case Pawn::LETTER_BLACK : // en passant possible?
					$board[ strtolower($boardStr[$cp+1]) ][ hexdec($boardStr[$cp+2]) ] =
						new Pawn(ctype_upper($boardStr[$cp]), ctype_upper($boardStr[$cp+1]));
					break;
				case Bishop::LETTER_BLACK :
					$board[ strtolower($boardStr[$cp+1]) ][ hexdec($boardStr[$cp+2]) ] =
						new Bishop(ctype_upper($boardStr[$cp]));
					break;
				case Knight::LETTER_BLACK :
					$board[ strtolower($boardStr[$cp+1]) ][ hexdec($boardStr[$cp+2]) ] =
						new Knight(ctype_upper($boardStr[$cp]));
					break;
				case Rook::LETTER_BLACK : // castling possible?
					$board[ strtolower($boardStr[$cp+1]) ][ hexdec($boardStr[$cp+2]) ] =
						new Rook(ctype_upper($boardStr[$cp]), ctype_upper($boardStr[$cp+1]));
					break;
				case Queen::LETTER_BLACK :
					$board[ strtolower($boardStr[$cp+1]) ][ hexdec($boardStr[$cp+2]) ] =
						new Queen(ctype_upper($boardStr[$cp]));
					break;
				case King::LETTER_BLACK : // castling possible?
					$board[ strtolower($boardStr[$cp+1]) ][ hexdec($boardStr[$cp+2]) ] =
						new King(ctype_upper($boardStr[$cp]), ctype_upper($boardStr[$cp+1]));
					break;
			}
		}
		return $board;
	}

	/**
	 * Renders a string representation of a given chess board (array)
	 * @see  Game::$boardString
	 * @see  Game::boardFromString()
	 * @param  array $board
	 * @return string
	 */
	public static function boardToString($board) {
		$boardStr = '';
		for ( $file='a' ; $file<='h' ; $file++ ) {
			for ( $rank=1; $rank<=8 ; $rank++ ) {
				if ($cp = $board[$file][$rank]) {
					$boardStr .= $cp;
					switch ($cp->getClass()) {
						case 'Pawn' :
							$boardStr .= $cp->canEnPassant ? strtoupper($file) : $file;
							break;
						case 'King' :
							$boardStr .= $cp->canCastle ? strtoupper($file) : $file;
							break;
						case 'Rook' :
							$boardStr .= $cp->canCastle ? strtoupper($file) : $file;
							break;
						default :
							$boardStr .= $file;
							break;
					}
					$boardStr .= $rank;
				}
			}
		}
		foreach ($board['x'] as $i => $cp) $boardStr .= $cp . 'x' . dechex($i);
		foreach ($board['y'] as $i => $cp) $boardStr .= $cp . 'y' . dechex($i);
		return $boardStr;
	}
	
	/**
	 * When was the game last updated?
	 * @return 	integer 	UNIX timestamp
	 */
	public function getLastUpdate() {
		return $this->lastUpdate;
	}
	
	/**
	 * Returns white player's name
	 * @return 	User
	 */
	public function getWhitePlayer() {
		return $this->whitePlayer;
	}
	
	/**
	 * Returns black player's name
	 * @return 	User
	 */
	public function getBlackPlayer() {
		return $this->blackPlayer;
	}
	
	/**
	 * For use in URLs
	 * @return 	string
	 */
	public function getRoute() {
		return 'Game/' . $this->gameHash;
	}
	
	/**
	 * Returns this games current status.
	 * @see 	Game constants
	 * @return 	integer
	 */
	public function getStatus() {
		return $this->status;
	}
	
	/**
	 * Returns a string explaining this
	 * games status in a user presentable way.
	 * @return 	string
	 */
	public function getFormattedStatus() {
		if (!$this->isOver()) {
			return Core::getLanguage()->getLanguageItem(
				'game.status.nextturn',
				array('u' => $this->getCurrentPlayer())
			);
		} elseif ($this->isDraw()) {
			return Core::getLanguage()->getLanguageItem('game.status.draw');
		} else {
			return Core::getLanguage()->getLanguageItem(
				'game.status.won',
				array('u' => $this->getCurrentPlayer())
			);
		}
 		return $this->status;
	}
	
	/**
	 * Wether or not this game is over.
	 * @return 	boolean
	 */
	public function isOver() {
		return $this->status >= self::STATUS_RESIGNED;
	}
	
	/**
	 * Wether or not this game has ended in
	 * a draw. False if still running or
	 * one player has won.
	 * @return 	boolean
	 */
	public function isDraw() {
		return $this->status >= self::STATUS_DRAW;
	}
	
	/**
	 * Returns the player who is active
	 * according to status.
	 * This _may_ mean that it is his turn.
	 * @return 	User
	 */
	public function getCurrentPlayer() {
		return $this->whitesTurn() ? $this->whitePlayer : $this->blackPlayer;
	}
	
	/**
	 * Wether or not it is white player's turn
	 * @return 	boolean
	 */
	public function whitesTurn() {
		return (boolean) ($this->status % 2);
	}
	
	/**
	 * Checks if the user is white,
	 * returns null if he is not a player in this game.
	 * @return 	boolean
	 */
	public function isWhitePlayer() {
		if (Core::getUser()->getId() == $this->whitePlayer->getId()) {
			return true;
		}
		if (Core::getUser()->getId() == $this->blackPlayer->getId()) {
			return false;
		}
		return null;
	}
}
