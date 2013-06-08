<?php

abstract class ChessPiece {
	
	/**
	 * Color as boolean:
	 * white = true, black = false
	 * @var 	boolean
	 */
	protected $white;
	
	/**
	 * ChessPiece color as boolean
	 * @var 	boolean
	 */
	const WHITE = true;
	
	/**
	 * ChessPiece color as boolean
	 * @var 	boolean
	 */
	const BLACK = false;
	
	/**
	 * This ChessPiece's position: file (column)
	 * @var 	string
	 */
	protected $file;
	
	/**
	 * This ChessPiece's position: rank (row)
	 * @var 	integer
	 */
	protected $rank;
	
	/**
	 * HTML's UTF-8 entities for chess characters
	 * @var 	array<string>
	 */
	public static $utf8Entities = array(
		"bKing" => "&#x2654;",
		"bQueen" => "&#x2655;",
		"bRook" => "&#x2656;",
		"bBishop" => "&#x2657;",
		"bKnight" => "&#x2658;",
		"bPawn" => "&#x2659;",
		"wKing" => "&#x265A;",
		"wQueen" => "&#x265B;",
		"wRook" => "&#x265C;",
		"wBishop" => "&#x265D;",
		"wKnight" => "&#x265E;",
		"wPawn" => "&#x265F;",
		);
	
	abstract public function validateMove($move);
	
	/**
	 * Creates a ChessPiece. Color is required.
	 * @param 	boolean 	$white
	 * @param 	string 		$square
	 */
	public function __construct($white, $square = '') {
		$this->white = $white;
	}
	
	/**
	 * Returns this ChessPiece's position as string
	 * or NULL if it is not known.
	 * @return 	string
	 */
	public function getSquare() {
		if (isset($this->file) && isset($this->rank)) {
			return $this->file.$this->rank;
		} else {
			return NULL;
		}
	}
		
	/**
	 * Returns this ChessPiece's utf-8 html entity.
	 * @return 	string
	 */
	public function __toString() {
		return self::$utf8Entities[(($this->white) ? 'w' : 'b').get_class($this)];
	}
}
