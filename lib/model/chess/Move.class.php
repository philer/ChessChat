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
		$moveString = strtoupper(preg_replace(
			'@' . self::SEPERATOR_PATTERN . '@',
			'',
			$moveString
		));
		
		if (is_numeric($moveString[0])) {
			$this->fromRank = strtolower($moveString[0]);
			$this->fromFile = strtolower($moveString[1]);
		} else {
			$this->fromRank = strtolower($moveString[1]);
			$this->fromFile = strtolower($moveString[0]);
		}
		if (is_numeric($moveString[2])) {
			$this->toRank = strtolower($moveString[2]);
			$this->toFile = strtolower($moveString[3]);
		} else {
			$this->toRank = strtolower($moveString[3]);
			$this->toFile = strtolower($moveString[2]);
		}
		
		$this->chesspiece = $game->board[$this->fromFile][$this->fromRank];
		$this->target     = $game->board[$this->toFile][$this->toRank];
		
		// validation
		if (Core::getUser()->getId() != $game->getCurrentPlayer()->getId()) {
			$this->setInvalid('chess.invalidmove.notyourturn');
		} elseif ($this->chesspiece == null) {
			$this->setInvalid('chess.invalidmove.nopiece');
		} elseif ($this->target != null && $this->chesspiece->isWhite() == $this->target->isWhite()) {
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
		return $this->from() . '-' . $this->to();
	}
	
	/**
	 * Where does this move start?
	 * @return 	string
	 */
	public function from() {
		return strtoupper($this->fromFile) . $this->fromRank;
	}

	/**
	 * Where does this move go?
	 * @return string
	 */
	public function to() {
		return strtoupper($this->toFile) . $this->toRank;
	}
	
	public function getChesspiece() {
		return $this->chesspiece;
	}
	
	public function getTarget() {
		return $this->target;
	}
	
	/**
	 * Returns a json encoded (string) representation of this Move's relevant
	 * information for use in ajax response
	 * @return array
	 */
	public function ajaxData() {
		$ajaxData = array(
			'from'  => $this->from(),
			'to'    => $this->to(),
			'valid' => $this->valid,
		);
		if (!$this->valid) {
			$ajaxData['invalidReason'] = Core::getLanguage()->getLanguageItem($this->invalidReason);
		}
		return $ajaxData;
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
				. Move::SEPERATOR_PATTERN . '?'
				. Move::COORDINATE_PATTERN
				. '$@'
			, $str);
		// OPTIONAL add support for algebraic notation
		// $piece = '[pkqnbrPKQNBR]'; // language support maybe?
		// return preg_match('@^'.$square.$separator.$square.'|'.$piece.$square.'$@', $str);
	}
}
