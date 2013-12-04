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
	 * Id of last move
	 * @var integer
	 */
	protected $lastMoveId = 0;
	
	/**
	 * Chessboard
	 * @var Board
	 */
	public $board = null;
	
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
	 * Game status for finished game
	 * @var  integer 0b1
	 */
	const STATUS_OVER = 1;
	
	/**
	 * Game status for white player's turn
	 * @var integer 0b10
	 */
	const STATUS_WHITES_TURN = 2;
	
	/**
	 * Game status for check
	 * @var integer 0b100
	 */
	const STATUS_CHECK = 4;
	
	/**
	 * Game status for a draw offering by last turn's player.
	 * After the game is finished this indicates if the draw
	 * offer was accepted.
	 * @var integer 0b1000
	 */
	const STATUS_DRAW_OFFERED = 8;
	
	/**
	 * Game status for last player's resignation
	 * @var integer 0b10000
	 */
	const STATUS_RESIGNED = 16;
	
	/**
	 * Game status for last player's achieved stalemate
	 * @var integer 0b100000
	 */
	const STATUS_STALEMATE = 32;
	
	/**
	 * Creates a Game object using provided data
	 * or a new game if none is provided.
	 * @param 	array<mixed> $gameData
	 */
	public function __construct(array $gameData = array()) {
		$whitePlayerData = array();
		$blackPlayerData = array();
		if (isset($gameData['whitePlayerId'])) {
			$whitePlayerData['userId'] = $gameData['whitePlayerId'];
		}
		if (isset($gameData['whitePlayerName'])) {
			$whitePlayerData['userName'] = $gameData['whitePlayerName'];
		}
		if (isset($gameData['blackPlayerId'])) {
			$blackPlayerData['userId'] = $gameData['blackPlayerId'];
		}
		if (isset($gameData['blackPlayerName'])) {
			$blackPlayerData['userName'] = $gameData['blackPlayerName'];
		}
		$this->whitePlayer = new User($whitePlayerData);
		$this->blackPlayer = new User($blackPlayerData);
		if (isset($gameData['boardString'])) {
			// $this->board = self::boardFromString($gameData['boardString']);
			$this->board = new Board($gameData['boardString']);
		}
		// delete unneeded data
		unset($gameData['whitePlayerId'],
			  $gameData['whitePlayerName'],
			  $gameData['blackPlayerId'],
			  $gameData['blackPlayerName'],
			  $gameData['boardString']
		);
		if (empty($gameData)) {
			$this->gameHash = $this->generateHash();
			// $this->status = self::STATUS_WHITES_TURN;
		} else {
			parent::__construct($gameData);
		}
		settype($this->status, 'int'); // came as string from database (use PDO instead maybe)
	}
	
	/**
	 * Returns this game's gameId
	 * @return 	integer
	 */
	public function getId() {
		return $this->gameId;
	}
	
	/**
	 * Returns this game's hash.
	 * @return 	string
	 */
	public function getHash() {
		return $this->gameHash;
	}
	
	/**
	 * Creates a hopefully unique hash for game identification.
	 * It containes digits and case sensitive letters
	 */
	protected function generateHash() {
		// put anything useful in the gamehash.
		// it doesn't have to be cryptographically safe,
		// just don't stumble over it.
		return Util::urlHash(
			$this->whitePlayer->getId()
			. $this->blackPlayer->getId()
			. microtime(true)
			. GAME_SALT,
			GAME_HASH_LENGTH
		);
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
	 * For use in URLs
	 * @return 	string
	 */
	public function getRoute() {
		// return 'Game/' . $this->gameHash;
		return $this->gameHash;
	}
	
	/**
	 * When was the game last updated?
	 * @return 	integer 	UNIX timestamp
	 */
	public function getLastUpdate() {
		return $this->lastUpdate;
	}
	
	/**
	 * Returns the moveId of the last move that was
	 * executed in this game
	 * @return int
	 */
	public function getLastMoveId() {
		return $this->lastMoveId;
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
	 * Returns the player who is active
	 * according to status.
	 * This _may_ mean that it is his turn.
	 * @return 	User
	 */
	public function getCurrentPlayer() {
		return $this->whitesTurn() ? $this->whitePlayer : $this->blackPlayer;
	}
	
	/**
	 * Checks if the given user is a player in this game.
	 * If no User objetc is provided, checks for the active
	 * request's User.
	 * @param  User    $user	optional
	 * @return boolean
	 */
	public function isPlayer(User $user = null) {
		if (is_null($user)) $user = Core::getUser();
		return $this->whitePlayer->getId() === $user->getId()
		    || $this->blackPlayer->getId() === $user->getId();
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
	 * Convenience Method for use in status setters
	 * that set a status flag to either true or false
	 * @param int     $flag  Game::STATUS_<constant>
	 * @param boolean $value
	 */
	protected function setStatusFlag($flag, $value) {
		if ($value) {
			$this->status |= $flag;
		} else {
			$this->status &= !$flag;
		}
	}
	
	/**
	 * Wether or not this game is over.
	 * @return 	boolean
	 */
	public function isOver() {
		return (boolean) ($this->status & Game::STATUS_OVER);
	}
	
	/**
	 * Mark this game as over
	 */
	public function setOver() {
		$this->status |= Game::STATUS_OVER;
	}
	
	/**
	 * Wether or not it is white player's turn
	 * @return 	boolean
	 */
	public function whitesTurn() {
		return (boolean) ($this->status & Game::STATUS_WHITES_TURN);
	}
	
	/**
	 * Toggle Player
	 * @return  Game (chaining)
	 */
	public function setNextTurn() {
		$this->setStatusFlag(Game::STATUS_WHITES_TURN, !$this->whitesTurn());
		return $this;
	}
	
	/**
	 * King is in check
	 * @param   boolean $check
	 */
	public function setCheck($check) {
		$this->setStatusFlag(Game::STATUS_CHECK, $check);
	}
	
	/**
	 * Whether or not this game has ended in
	 * a draw. False if still running or
	 * one player has won.
	 * @return 	boolean
	 */
	public function isDraw() {
		return $this->isOver() && $this->drawOffered();
	}
	
	/**
	 * Whether or not a draw has been offered by the last player
	 * @see   Game::STATUS_DRAW_OFFERED
	 * @return boolean
	 */
	public function drawOffered() {
		return (boolean) ($this->status & Game::STATUS_DRAW_OFFERED);
	}
	
	/**
	 * Set drawOffered
	 * @see   Game::STATUS_DRAW_OFFERED
	 * @param boolean $offered
	 */
	public function setDrawOffered($offered) {
		$this->setStatusFlag(Game::STATUS_DRAW_OFFERED, $offered);
	}
	
	/**
	 * Set resigned status to true (game over)
	 * @see  Game::STATUS_RESIGNED
	 */
	public function setResigned() {
		$this->status |= Game::STATUS_RESIGNED;
	}
	
	/**
	 * Set stalemate to true (game over)
	 * @see  Game::STATUS_STALEMATE
	 */
	public function setStalemate() {
		$this->status |= Game::STATUS_STALEMATE;
	}
	
	/**
	 * Checks and executes a given Move.
	 * @param  Move $move
	 * @return Game (chaining)
	 */
	public function move(Move $move) {
		$this->board->move($move);
		// $this->lastMoveId = $move->moveId;
		return $this;
	}
	
	/**
	 * Save a Game (new db entry)
	 */
	public function save() {
		Core::getDB()->sendQuery("
			INSERT INTO cc_game (gameHash, whitePlayerId, blackPlayerId, board, status)
			VALUES ('" . $this->gameHash . "',
			        " . $this->whitePlayer->getId() . ",
			        " . $this->blackPlayer->getId() . ",
			        '" . Board::DEFAULT_STRING . "',
			        " . self::STATUS_WHITES_TURN . ")
		");
	}

	/**
	 * Save changed game (update existing db entry)
	 */
	public function update() {
		Core::getDB()->sendQuery("
			UPDATE cc_game SET
				board      = '" . $this->board . "',
				status     = " . $this->status . "
			WHERE gameId = " . $this->gameId
		);
	}
}
