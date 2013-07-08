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
	 * Checks if $move is a valid move for this chess piece
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 */
	abstract public function validateMove(Move &$move);
	
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
		return $this->white ? static::UTF8_WHITE : static::UTF8_BLACK;
		// return self::$utf8Entities[(($this->white) ? 'w' : 'b') . get_class($this)];
	}
	
	/**
	 * Is this a white chesspiece?
	 * @return 	boolean
	 */
	public function isWhite() {
		return $this->white;
	}
}
