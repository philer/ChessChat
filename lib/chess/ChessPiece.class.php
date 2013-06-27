<?php

/**
 * Represents a single chess piece
 * @author Philipp Miller
 */
abstract class ChessPiece {
	
	/**
	 * Color as boolean:
	 * white = true, black = false
	 * @var 	boolean
	 */
	protected $white;
	
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
		"wKing" => "&#x2654;",
		"wQueen" => "&#x2655;",
		"wRook" => "&#x2656;",
		"wBishop" => "&#x2657;",
		"wKnight" => "&#x2658;",
		"wPawn" => "&#x2659;",
		"bKing" => "&#x265A;",
		"bQueen" => "&#x265B;",
		"bRook" => "&#x265C;",
		"bBishop" => "&#x265D;",
		"bKnight" => "&#x265E;",
		"bPawn" => "&#x265F;",
		);
	
	abstract public function validateMove($move);
	
	/**
	 * Creates a ChessPiece. Color is required.
	 * @param 	boolean 	$white
	 * @param 	string 		$square
	 */
	public function __construct($white, $square = '') {
		$this->white = $white;
		// TODO
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
		return self::$utf8Entities[(($this->white) ? 'w' : 'b') . get_class($this)];
	}
	
	/**
	 * Is this a white chesspiece?
	 * @return 	boolean
	 */
	public function isWhite() {
		return $this->white;
	}
}
