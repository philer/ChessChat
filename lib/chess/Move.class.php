<?php

/**
 * Represents a chess move
 * @author Philipp Miller
 */
class Move {
	
	/**
	 * Rank (horizontal, row) offset caused by this move.
	 * @var integer
	 */
	protected $rankOffset = 0;
	
	/**
	 * File (vertical, column) offset caused by this move.
	 * @var integer
	 */
	protected $fileOffset = 0;
	
	/**
	 * Once the move has been checked it will be flagged as (not) valid.
	 * @var boolean
	 */
	public $valid = false;
	
	/**
	 * If the move has been flagged as invalid this message
	 * should explain why. (hint: use language variables)
	 * @var string
	 */
	public $invalidReason = '';
	
	/**
	 * String representation of this move, formatted on construction
	 * @var string
	 */
	protected $moveString = '';
	
	/**
	 * Creates a Move object from the given string.
	 * @param 	string 	$moveString 	has to be valid but not system formatted
	 */
	public function __construct($moveString) {
		if (self::patternMatch($moveString)) {
			$this->moveString = self::formatMoveString($moveString);
		} else throw new Exception('Invalid move string');
		// TODO larissa
		// set offsets, write getters
		// set $valid and $invalidReason where it is appropriate
	}
	
	/**
	 * When treated as string a Move object will
	 * return it's formatted string representation
	 * @return 	string
	 */
	public function __toString() {
		return $this->moveString;
	}
	
	/**
	 * Checks if given string may be a move
	 * pattern supported by this system.
	 * DOES NOT validate or execute the move.
	 * @param 	string 	$str
	 * @return 	boolean
	 */
	public static function patternMatch($str) {
		$square = '([a-hA-H][1-8]|[1-8][a-hA-H])';
		$separator = '[_ -]?';
		return preg_match('@^'.$square.$separator.$square.'$@', $str);
		// OPTIONAL add support for algebraic notation
		// $piece = '[pkqnbrPKQNBR]'; // language support maybe?
		// return preg_match('@^'.$square.$separator.$square.'|'.$piece.$square.'$@', $str);
	}
	
	/**
	 * Returns a system friendly formatted string
	 * representation of a valid move string,
	 * e.g. "A4-B5".
	 * @param 	string 	$moveString
	 * @return 	string
	 */
	public static function formatMoveString($moveString) {
		// TODO larissa
		// put letters in first place (3A -> A3)
		// replace possible seperators with -
		return strtoupper($moveString);
		// OPTIONAL add support for algebraic notation
	}
	
}
