<?php

/**
 * Represents a King chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class King extends ChessPiece {
	
	/**
	 * King may only perform castling if he hasn't been moved before.
	 * @var boolean
	 */
	protected $canCastle = false;
	
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
	 * Chess notation letter for this chess piece (english)
	 * White is upper case.
	 * @var string
	 */
	const LETTER_WHITE = 'K';
	
	/**
	 * Chess notation letter for this chess piece (english)
	 * black is lower case.
	 * @var string
	 */
	const LETTER_BLACK = 'k';
	
	public function __construct($white, $canCastle = false) {
		parent::__construct($white);
		$this->canCastle = $canCastle;
	}
	
	/**
	 * Check if $move is a valid move for a King
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 * Valid move for a king:
	 * A king can move one square in any direction
	 */
	public function validateMove(Move &$move) {
		if (abs($move->getRankOffset()) > 1 || abs($move->getFileOffset()) > 1) {
			$move->setInvalid('chess.invalidmove.king');
		}
	}
}
