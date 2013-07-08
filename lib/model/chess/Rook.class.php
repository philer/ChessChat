<?php

/**
 * Represents a Rook chess piece.
 * @author Philipp Miller
 */
class Rook extends ChessPiece {
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * white Rook
	 * @var 	string
	 */
	const UTF8_WHITE = '&#x2656;';
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * black Rook
	 * @var 	string
	 */
	const UTF8_BLACK = '&#x265C;';
	
	/**
	 * Check if $move is a valid move for a Rook
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 */
	public function validateMove(Move &$move) {
		$move->valid = true;
		// $move->valid = false;
		// $move->invalidReason = 'You Suck!';
	}
}
