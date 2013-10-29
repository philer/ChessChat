<?php

/**
 * Represents a chess move
 * @author Philipp Miller, Larissa Hammerstein
 */
class Move {
	
	/**
	 * From where did we move?
	 * @var string
	 */
	public $fromFile = '';
	
	/**
	 * Where did we move?
	 * @var string
	 */
	public $toFile = '';

	/**
	 * From where did we move?
	 * @var string
	 */
	public $fromRank = '';
	
	/**
	 * Where did we move?
	 * @var string
	 */
	public $toRank = '';
	
	/**
	 * File (vertical, column) offset caused by this move.
	 * @var integer
	 */
	public $fileOffset = 0;
	
	/**
	 * Rank (horizontal, row) offset caused by this move.
	 * @var integer
	 */
	public $rankOffset = 0;
	
	/**
	 * Chesspiece that was moved.
	 * @var ChessPiece
	 */
	protected $chesspiece = null;
	
	/**
	 * Chesspiece that was taken (if any).
	 * @var ChessPiece
	 */
	protected $target = null;

	/**
	 * Once the move has been checked it will be flagged as (not) valid.
	 * @var boolean
	 */
	protected $valid = true;
	
	/**
	 * If the move has been flagged as invalid this message
	 * should explain why. (hint: use language variables)
	 * @var string
	 */
	protected $invalidReason = '';

	/**
	 * Coordinates may be written like this
	 */
	const COORDINATE_PATTERN = '([a-hA-H][1-8]|[1-8][a-hA-H])';

	/**
	 * Seperator for coordinate notation
	 */
	const SEPERATOR_PATTERN = '[_ -]';

	/**
	 * Creates a Move object from the given string.
	 * @param string $moveString has to be valid but not system formatted
	 * @param Game   $game       game in which this move was made
	 */
	public function __construct($moveString, Game $game) {
		if (!self::patternMatch($moveString)) throw new Exception('chess.invalidmove.format');
		$moveString = strtoupper(preg_replace(Move::SEPERATOR_PATTERN, '', $moveString));

		if (is_numeric($moveString[0])) {
			$this->fromRank = $moveString[0];
			$this->fromFile = $moveString[1];
		} else {
			$this->fromRank = $moveString[1];
			$this->fromFile = $moveString[0];
		}
		if (is_numeric($moveString[3])) {
			$this->toRank = $moveString[3];
			$this->toFile = $moveString[4];
		} else {
			$this->toRank = $moveString[4];
			$this->toFile = $moveString[3];
		}
		
		$this->chesspiece = $game->board[$this->fromRank][$this->fromFile];
		$this->target     = $game->board[$this->toRank  ][$this->toFile];
		$this->fileOffset = $this->toFile - $this->fromFile;
		$this->rankOffset = $this->toRank - $this->fromRank;
		

		// validation
		if ($this->chesspiece === $this->target) {
			$this->setInvalid('chess.invalidmove.nomove');
		} elseif ($this->chesspiece->isWhite() == $this->target->isWhite()) {
			$this->setInvalid('chess.invalidmove.owncolor');
		} else {
			$this->chesspiece->validateMove($this, $game);
		}
	}
	
	/**
	 * When treated as string a Move object will
	 * return it's formatted string representation
	 * @return 	string
	 */
	public function __toString() {
		return $this->fromFile . $this->fromRank . '-' . $this->toFile . $this->toRank;
	}
	
	/**
	 * When a move turns out to be invalid, use this function to flag it and
	 * give a reason
	 * @param string $reason why is this move invalid? use language variables
	 */
	public function setInvalid($reason = "") {
		$this->valid = false;
		$this->invalidReason = $reason;
	}

	/**
	 * Move okay?
	 * @return boolean
	 */
	public function isValid() {
		return $this->valid;
	}

	/**
	 * Why was this move flagged as invalid?
	 * @return string
	 */
	public function getInvalidReason() {
		return $this->invalidReason;
	}
	
	public funtion getRankOffset() {
		return $this->rankOffset;
	}
	
	public funtion getFileOffset() {
		return $this->fileOffset;
	}
	
	

	/**
	 * Checks if given string may be a move
	 * pattern supported by this system.
	 * DOES NOT validate or execute the move.
	 * @param 	string 	$str
	 * @return 	boolean
	 */
	public static function patternMatch($str) {
		return preg_match('@^'
				. Move::COORDINATE_PATTERN
				. Move::SEPERATOR_PATTERN
				. Move::COORDINATE_PATTERN
				. '$@'
			, $str);
		// OPTIONAL add support for algebraic notation
		// $piece = '[pkqnbrPKQNBR]'; // language support maybe?
		// return preg_match('@^'.$square.$separator.$square.'|'.$piece.$square.'$@', $str);
	}
	
}
