<?php

/**
 * Represents a chess move
 * @author Philipp Miller, Larissa Hammerstein
 */
class Move extends DatabaseModel {
	
	/**
	 * Anything has and Id nowadays
	 * @var integer
	 */
	public $moveId = 0;
	
	/**
	 * Square where we start
	 * @var Square
	 */
	public $from = null;
	
	/**
	 * Square where we arrive
	 * @var Square
	 */
	public $to = null;
	
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
	 * Current game
	 * @var Game
	 */
	protected $game = null;
	
	/**
	 * Seperator for coordinate notation
	 */
	const SEPERATOR_PATTERN = '[_ -]';
	
	/**
	 * Creates a Move object from the given string.
	 * @param string $moveData has to be valid but not system formatted
	 * @param Game   $game       game in which this move was made
	 */
	public function __construct($moveData, Game $game = null) {
		if (is_array($moveData)) { // data from database
			
			$this->from = new Square($moveData['fromSquare'], ChessPiece::getInstance($moveData['chessPiece']));
			$this->to   = new Square($moveData['toSquare']);
			
			$this->moveId = $moveData['moveId'];
			
		} elseif (self::patternMatch($moveData) && $game !== null) { // new move
			
			$this->game = $game;
			$moveData = preg_replace('@' . self::SEPERATOR_PATTERN . '@', '', $moveData);
			
			// 'clone' to make sure we can execute this move without changing it
			$this->from = clone $this->game->board->{ $moveData[0] . $moveData[1] };
			$this->to   = clone $this->game->board->{ $moveData[2] . $moveData[3] };
			
			if (empty($invalidMsg)) $this->validate();
			else $this->setInvalid($invalidMsg);
			
		}
	}
	
	/**
	 * When treated as string a Move object will
	 * return it's formatted string representation
	 * @return 	string
	 */
	public function __toString() {
		return $this->from . '-' . $this->to;
	}
	
	public function formatString() {
		if ($this->isValid()) {
			return Core::getLanguage()->getLanguageItem(
				'chess.moved',
				array(
					'user'  => Core::getUser(),
					'piece' => $this->from->chesspiece->utf8(),
					'from'  => $this->from,
					'to'    => $this->to
				)
			);
		} else {
			return Core::getLanguage()->getLanguageItem($this->invalidReason);
		}
	}
	
	public function validate() {
		if (Core::getUser()->getId() != $this->game->getCurrentPlayer()->getId()) {
			$this->setInvalid('chess.invalidmove.notyourturn');
		} elseif ($this->from->isEmpty()) {
			$this->setInvalid('chess.invalidmove.nopiece');
		} elseif (!$this->to->isEmpty() && $this->from->chesspiece->isWhite() == $this->to->chesspiece->isWhite()) {
			$this->setInvalid('chess.invalidmove.owncolor');
		} else {
			$this->from->chesspiece->validateMove($this, $this->game->board);
		}
	}
	
	public function getRankOffset() {
		return $this->to->rank() - $this->from->rank();
	}
	
	public function getFileOffset() {
		return $this->to->file() - $this->from->file();
	}
	
	public function getPath() {
		return $this->game->board->range($this->from, $this->to);
	}
	
	/**
	 * Move okay?
	 * @return boolean
	 */
	public function isValid() {
		return $this->valid;
	}
	
	/**
	 * When a move turns out to be invalid, use this function to flag it and
	 * give a reason
	 * @param string $reason why is this move invalid? use language variables
	 */
	public function setInvalid($reason = '') {
		$this->valid = false;
		$this->invalidReason = $reason;
	}
	
	/**
	 * Why was this move flagged as invalid?
	 * @return string
	 */
	public function getInvalidReason() {
		return $this->invalidReason;
	}
	
	/**
	 * Returns a json encoded (string) representation of this Move's relevant
	 * information for use in ajax response
	 * @return array
	 */
	public function ajaxData() {
		$ajaxData = array(
			'id'    => $this->moveId,
			'from'  => (string) $this->from,
			'to'    => (string) $this->to,
			'valid' => $this->isValid(),
		);
		// if (!$this->isValid()) {
		// 	$ajaxData['invalidReason'] = Core::getLanguage()->getLanguageItem($this->invalidReason);
		// }
		return $ajaxData;
	}
	
    public function save() {
        Core::getDB()->sendQuery("
            INSERT INTO cc_move (gameId, playerId, chessPiece, fromSquare, toSquare)
            VALUES (" . $this->game->getId() . ",
                    " . Core::getUser()->getId() . ",
                    '" . $this->from->chesspiece->letter() . "',
                    '" . $this->from . "',
                    '" . $this->to . "')
        ");
        $this->moveId = Core::getDB()->getLastInsertId();
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
				. Square::PATTERN
				. self::SEPERATOR_PATTERN . '?'
				. Square::PATTERN
				. '$@'
			, $str);
		// OPTIONAL add support for algebraic notation
		// $piece = '[pkqnbrPKQNBR]'; // language support maybe?
		// return preg_match('@^'.$square.$separator.$square.'|'.$piece.$square.'$@', $str);
	}
}
