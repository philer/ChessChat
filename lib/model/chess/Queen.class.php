<?php

/**
 * Represents a Rook chess piece.
 * @author Philipp Miller
 */
class Queen extends ChessPiece {
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * white Queen
	 * @var 	string
	 */
	const UTF8_WHITE = '&#x2655;';
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * black Queen
	 * @var 	string
	 */
	const UTF8_BLACK = '&#x265B;';
	
	/**
	 * Check if $move is a valid move for a Queen
	 * and sets $move->valid and $move->invalidMessage accordingly
	 * @param 	Move 	$move
	 */
	public function validateMove(Move &$move) {
		$move->valid = true;
		// $move->valid = false;
		// $move->invalidReason = 'You Suck!';
	}
}
