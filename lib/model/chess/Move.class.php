<?php

/**
 * Represents a chess move
 * @author Philipp Miller, Larissa Hammerstein
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
	 * Chesspiece that was moved.
	 * @var ChessPiece
	 */
	public $chesspiece = null;
	
	/**
	 * From where did we move?
	 * @var string
	 */
	public $from = '';
	
	/**
	 * Where did we move?
	 * @var string
	 */
	public $to = '';
	
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
		// set offsets, $from, $to, $chesspiece
		// write getters
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
		// OPTIONAL add support for algebraic notation
		// put letters in first place (3A -> A3)
		// replace possible seperators with -
		
		$moveString = trim($moveString); 
		
		$tmpMoveString = "XX-XX";
		

		
		if(strtoupper($moveString[0])=='A'
		|| strtoupper($moveString[0])=='B'
		|| strtoupper($moveString[0])=='C'
		|| strtoupper($moveString[0])=='D'
		|| strtoupper($moveString[0])=='E'
		|| strtoupper($moveString[0])=='F')	{
			$tmpMoveString[0]=strtoupper($moveString[0])
		}
		elseif($moveString[0]=='1'
		|| $moveString[0]=='2'
		|| $moveString[0]=='3'
		|| $moveString[0]=='4'
		|| $moveString[0]=='5'
		|| s$moveString[0]=='6')	{
			$tmpMoveString[0]=strtoupper($moveString[1])
		}	
	
		
		if(strtoupper($moveString[1])=='A'
		|| strtoupper($moveString[1])=='B'
		|| strtoupper($moveString[1])=='C'
		|| strtoupper($moveString[1])=='D'
		|| strtoupper($moveString[1])=='E'
		|| strtoupper($moveString[1])=='F')	{
			$tmpMoveString[0]=strtoupper($moveString[0])
		}
		elseif($moveString[1]=='1'
		|| $moveString[1]=='2'
		|| $moveString[1]=='3'
		|| $moveString[1]=='4'
		|| $moveString[1]=='5'
		|| s$moveString[1]=='6')	{
			$tmpMoveString[1]=strtoupper($moveString[1])
		}	
			
		if(strtoupper($moveString[strlen($moveString)-2])=='A'
		|| strtoupper($moveString[strlen($moveString)-2]])=='B'
		|| strtoupper($moveString[strlen($moveString)-2]])=='C'
		|| strtoupper($moveString[strlen($moveString)-2]])=='D'
		|| strtoupper($moveString[strlen($moveString)-2]])=='E'
		|| strtoupper($moveString[strlen($moveString)-2]])=='F')	{
			$tmpMoveString[3]=strtoupper($moveString[strlen($moveString)-2]])
		}
		elseif($moveString[strlen($moveString)-2]]=='1'
		|| $moveString[strlen($moveString)-2]]=='2'
		|| $moveString[strlen($moveString)-2]]=='3'
		|| $moveString[strlen($moveString)-2]]=='4'
		|| $moveString[strlen($moveString)-2]]=='5'
		|| s$moveString[strlen($moveString)-2]]=='6')	{
			$tmpMoveString[4]=strtoupper($moveString[strlen($moveString)-2]])
		}		
		
		if(strtoupper($moveString[strlen($moveString)-1])=='A'
		|| strtoupper($moveString[strlen($moveString)-1]])=='B'
		|| strtoupper($moveString[strlen($moveString)-1]])=='C'
		|| strtoupper($moveString[strlen($moveString)-1]])=='D'
		|| strtoupper($moveString[strlen($moveString)-1]])=='E'
		|| strtoupper($moveString[strlen($moveString)-1]])=='F')	{
			$tmpMoveString[3]=strtoupper($moveString[strlen($moveString)-2]])
		}
		elseif($moveString[strlen($moveString)-1]]=='1'
		|| $moveString[strlen($moveString)-1]]=='2'
		|| $moveString[strlen($moveString)-1]]=='3'
		|| $moveString[strlen($moveString)-1]]=='4'
		|| $moveString[strlen($moveString)-1]]=='5'
		|| s$moveString[strlen($moveString)-1]]=='6')	{
			$tmpMoveString[4]=strtoupper($moveString[strlen($moveString)-1]])
		}	

		if(strpos($tmpMoveString, 'X') === false){
			$moveString = $tmpMoveString;
			}
		else{
			$moveString = "Non formatable";
			}
		//TODO Fehlermeldung
		return strtoupper($moveString);
		
	}
	/**
	 * Returns boardarray index for file represantion letter
	 */
	 
	public  letterToIndex($letter) = array('A'=> 0, 'B' => 1, 'C' => 2, 'D' => 3, 'E' => 4, 'F' => 5);
	
	/**
	 * Returns boardarray index for rank represantion number
	 */
	 
	public  numberToIndex($number) = array(1 => 0, 2 => 1, 3 => 2, 4 => 3, 5 => 4, 6 => 5);
	
}
