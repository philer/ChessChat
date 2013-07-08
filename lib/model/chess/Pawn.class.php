<?php

/**
 * Represents a Rook chess piece.
 * @author Philipp Miller
 */
class Pawn extends ChessPiece {
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * white Pawn
	 * @var 	string
	 */
	const UTF8_WHITE = '&#x2659;';
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * black Pawn
	 * @var 	string
	 */
	const UTF8_BLACK = '&#x265F;';
	
	/**
	 * Check if $move is a valid move for a Pawn
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 */
	public function validateMove(Move &$move) {
		$move->valid = true;
		// $move->valid = false;
		// $move->invalidReason = 'You Suck!';
	}
}
