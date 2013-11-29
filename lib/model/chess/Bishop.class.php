<?php

/**
 * Represents a Bishop chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Bishop extends ChessPiece {
		
	/**
	 * HTML's UTF-8 entitie for chess character
	 * white Bishop
	 * @var string
	 */
	const UTF8_WHITE = '&#x2657;';
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * black Bishop
	 * @var string
	 */
	const UTF8_BLACK = '&#x265D;';
	
	/**
	 * Chess notation letter for this chess piece (english)
	 * White is upper case.
	 * @var string
	 */
	const LETTER_WHITE = 'B';
	
	/**
	 * Chess notation letter for this chess piece (english)
	 * black is lower case.
	 * @var string
	 */
	const LETTER_BLACK = 'b';
	
	/**
	 * Check if $move is a valid move for a Bishop
	 * and sets $move->valid and $move->invalidMessage accordingly
	 * @param 	Move 	$move
	 */
	public function validateMove(Move $move, Game $game) {
		// Valid move for a Bishop:
		// diagonal movement
		// no limits in distance
		// cannot jump over other pieces
		if (abs($move->getRankOffset()) != abs($move->getFileOffset())) {
			$move->setInvalid('chess.invalidmove.bishop');
			return;
		}
		for ( $i=0 ; $i<$move->getRankOffset()-1 ; $i++ ) {
			if ($game->board[chr(ord($move->fromFile) + $i)][$move->fromRank] != null) {
				$move->setInvalid('chess.invalidmove.blocked');
				return;
			}
		}
	}
}
