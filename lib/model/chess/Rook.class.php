<?php

/**
 * Represents a Rook chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Rook extends ChessPiece {
	
	/**
	 * King may only perform castling if the Rook hasn't been moved before.
	 * @var boolean
	 */
	protected $canCastle = false;
	
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
	 * Chess notation letter for this chess piece (english)
	 * White is upper case.
	 * @var string
	 */
	const LETTER_WHITE = 'R';
	
	/**
	 * Chess notation letter for this chess piece (english)
	 * black is lower case.
	 * @var string
	 */
	const LETTER_BLACK = 'r';

	public function __construct($white, $canCastle = false) {
		parent::__construct($white);
		$this->canCastle = $canCastle;
	}
	
	/**
	 * Check if $move is a valid move for a Rook
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 * Valid move for a Rook:
	 * The rook moves horizontally or vertically, 
	 * through any number of unoccupied squares
	 */
	public function validateMove(Move &$move) {
		if ($move->getRankOffset() * $move->getFileOffset() != 0) {
			$move->setInvalid('chess.invalidmove.rook');
			return;
		}
		if ($move->getRankOffset() == 0){
			for ( $i=0 ; $i<$move->getFileOffset()-1 ; $i++ ){
				if ($game->board[$move->fromRank][$i + $move->fromFile] != null) {
					$move->setInvalid('chess.invalidmove.blocked');
					return;
				}
			}
		} else {
			for ( $i=0 ; $i<$move->getRankOffset()-1 ; $i++ ){
				if ($game->board[$i + $move->fromRank][$move->fromFile] != null) {
					$move->setInvalid('chess.invalidmove.blocked');
					return;
				}
			}
		}

	}
}
