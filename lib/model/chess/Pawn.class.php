<?php

/**
 * Represents a Pawn chess piece.
 * @author Philipp Miller, Larissa Hammerstein
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
	 * Valid move for Pawn:
	 * does not move backwards
	 * normally advancing a single square
	 * the first time a pawn is moved, it has the option of advancing two squares
	 * Pawns may not use the initial two-square advance to jump over an occupied square, or to capture.
	 * A pawn captures diagonally, one square forward and to the left or right. 
	 */
	public function validateMove(Move &$move) {
		$move->valid = false;
		// the Pawn is white
		if($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Pawn(true)){
			// the first time a pawn is moved, it has the option of advancing two squares
			if($move[1] == 2 && $move[0] == $move[3] && move[4] == 4 && $array[$letterToIndex($move[0])][2] == null && $array[$letterToIndex($move[0])][3] == null){
				$move->valid = true;
				}
			// 	normally advancing a single square
			if($move[0] == $move[3] && $move[1] + 1 = $move[4] && $array[$letterToIndex($move[3])][$numberToIndex($move[4])] == null){
				$move->valid = true;}
			}
			// capturing
			if($move[1] + 1 = $move[4] && (abs($letterToIndex($move[0])-$letterToIndex($move[3]))  == 1)){
				if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == King(false)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Knight(false)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Pawn(false)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Queen(false)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Rook(false)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Bishop(false)){
					$move->valid = true;
				}
			}
		
		
		// the Pawn is black
		elseif($array[$letterToIndex($move[1])][$numberToIndex($move[1])] == Pawn(false)){
			// the first time a pawn is moved, it has the option of advancing two squares
			if($move[1] == 7 && $move[0] == $move[3] && move[4] == 5 && $array[$letterToIndex($move[0])][5] == null && $array[$letterToIndex($move[0])][4] == null){
				$move->valid = true;
			}
			// 	normally advancing a single square
			if($move[0] == $move[3] && $move[1] - 1 = $move[4] && $array[$letterToIndex($move[3])][$numberToIndex($move[4])] == null){
				$move->valid = true;
				}
			}
			//capturing
			if($move[1] - 1 = $move[4] && (abs($letterToIndex($move[0])-$letterToIndex($move[3]))  == 1)){
				if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == King(true)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Knight(true)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Pawn(true)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Queen(true)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Rook(true)
				|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Bishop(true)){
					$move->valid = true;
					}
				}
			}

		 $move->invalidReason = 'A Pawn cannot make a move like this';
	}
}
