<?php

/**
 * Represents a Rook chess piece.
 * @author Philipp Miller
 */
class Knight extends ChessPiece {
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * white Knight
	 * @var 	string
	 */
	const UTF8_WHITE = '&#x2658;';
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * black Knight
	 * @var 	string
	 */
	const UTF8_BLACK = '&#x265E;';
	
	/**
	 * Check if $move is a valid move for a Knight
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 */
	public function validateMove(Move &$move) {
		$move->valid = true;
		// $move->valid = false;
		// $move->invalidReason = 'You Suck!';
	}
}
