<?php

/**
 * Represents a Pawn chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Pawn extends ChessPiece {
	
	/**
	 * Pawn's may sometimes move 'en passant'
	 * @var boolean
	 */
	public $canEnPassant = false;

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
	 * Chess notation letter for this chess piece (english)
	 * White is upper case.
	 * @var string
	 */
	const LETTER_WHITE = 'P';
	
	/**
	 * Chess notation letter for this chess piece (english)
	 * black is lower case.
	 * @var string
	 */
	const LETTER_BLACK = 'p';
	
	public function __construct($white, $canEnPassant = false) {
		parent::__construct($white);
		$this->canEnPassant = $canEnPassant;
	}
	
	/**
	 * Check if $move is a valid move for a Pawn
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 */
	public function validateMove(Move $move, Game $game) {
		 // Valid move for Pawn:
		 // does not move backwards
		 // normally advancing a single square
		 // the first time a pawn is moved, it has the option of advancing two squares
		 // Pawns may not use the initial two-square advance to jump over an occupied square, or to capture.
		 // A pawn captures diagonally, one square forward and to the left or right. 
		if (abs($move->getRankOffset()) > 2) {
			$move->setInvalid('chess.invalidmove.pawn');
			return;
		}
		if($this->white){
			if($move->getRankOffset()<0){
				$move->setInvalid('chess.invalidmove.pawn');
				return;
			}
			// only valid for first move
			if($move->getRankOffset() == 2){
				if($move->fromRank != 2){
					$move->setInvalid('chess.invalidmove.pawn.notfirstmove');
				}
				if($game->board[$move->fromFile][3] != null){
					$move->setInvalid('chess.invalidmove.blocked');
				}
			}
		}
		else{
			if($move->getRankOffset()>0){
				$move->setInvalid('chess.invalidmove.pawn');
				return;
			}
			// only valid for first move
			if($move->getRankOffset() == -2){
				if($move->fromRank != 7){
					$move->setInvalid('chess.invalidmove.pawn.notfirstmove');
				}
				if($game->board[$move->fromFile][6] != null){
					$move->setInvalid('chess.invalidmove.blocked');
				}
			}
		}
	}
}
