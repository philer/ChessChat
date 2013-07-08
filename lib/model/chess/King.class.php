<?php

/**
 * Represents a Rook chess piece.
 * @author Philipp Miller
 */
class King extends ChessPiece {
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * white King
	 * @var 	string
	 */
	const UTF8_WHITE = '&#x2654;';
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * black King
	 * @var 	string
	 */
	const UTF8_BLACK = '&#x265A;';
	
	/**
	 * Check if $move is a valid move for a King
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 */
	public function validateMove(Move &$move) {
		$move->valid = true;
		// $move->valid = false;
		// $move->invalidReason = 'You Suck!';
	}
}
