<?php

/**
 * Represents a Rook chess piece.
 * @author Philipp Miller
 */
class Bishop extends ChessPiece {
		
	/**
	 * HTML's UTF-8 entitie for chess character
	 * white Bishop
	 * @var 	string
	 */
	const UTF8_WHITE = '&#x2657;';
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * black Bishop
	 * @var 	string
	 */
	const UTF8_BLACK = '&#x265D;';
	
	/**
	 * Check if $move is a valid move for a Bishop
	 * and sets $move->valid and $move->invalidMessage accordingly
	 * @param 	Move 	$move
	 */
	public function validateMove(Move &$move) {
		$move->valid = true;
		// $move->valid = false;
		// $move->invalidReason = 'You Suck!';
	}
}
