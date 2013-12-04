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
	protected $white = true;
	
	/**
	 * Creates a ChessPiece. Color is required.
	 * @param 	boolean 	$white
	 * @param 	string 		$square
	 */
	public function __construct($white) {
		$this->white = $white;
	}
	
	public static function getInstance($letter) {
		switch(strtolower($letter)) {
            case Pawn::LETTER_BLACK :   return new Pawn(  ctype_upper($letter));
            case Bishop::LETTER_BLACK : return new Bishop(ctype_upper($letter));
            case Knight::LETTER_BLACK : return new Knight(ctype_upper($letter));
            case Rook::LETTER_BLACK :   return new Rook(  ctype_upper($letter));
            case Queen::LETTER_BLACK :  return new Queen( ctype_upper($letter));
            case King::LETTER_BLACK :   return new King(  ctype_upper($letter));
        }
	}
	
	/**
	 * Checks if $move is a valid move for this chess piece
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 */
	abstract public function validateMove(Move $move, Board $board);
	
	public function __toString() {
		return $this->utf8();
	}
	
	/**
	 * Returns this ChessPiece's utf-8 html entity.
	 * @return 	string
	 */
	public function utf8() {
		return $this->white ? static::UTF8_WHITE : static::UTF8_BLACK;
	}

	/**
	 * Returns this ChessPiece's letter in chess notation.
	 * Capital letter represents white.
	 * @return 	string
	 */
	public function letter() {
		return $this->white ? static::LETTER_WHITE : static::LETTER_BLACK;
	}
	
	/**
	 * Is this a white chesspiece?
	 * @return 	boolean
	 */
	public function isWhite() {
		return $this->white;
	}
}
