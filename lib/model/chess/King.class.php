<?php

/**
 * Represents a King chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class King extends ChessPiece {
	
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
	 * Check if $move is a valid move for a King
	 * and sets $move->valid and $move->invalidMessage accordingly.
	 * @param 	Move 	$move
	 * Valid move for a king:
	 * A king can move one square in any direction
	 */
	public function validateMove(Move &$move) {
		$move->valid = false;
		if($move[0] == $move[3] && abs($move[1] - $move[4]) == 1 
		|| ($move[1] == $move[4] && abs($letterToIndex($move[0])-$letterToIndex($move[3])) == 1)
		|| (abs($move[1] - $move[4]) == 1 && abs($letterToIndex($move[0])-$letterToIndex($move[3])) == 1)){
					if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == null ){
						$move->$valid = true;
					}
				
					elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == King(true) )	{
						if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == King(false)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Knight(false)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Pawn(false)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Queen(false)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Rook(false)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Bishop(false))
						{
							$move->$valid = true;
						}
					}
				
					elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == King(false) ){
						if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == King(true)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Knight(true)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Pawn(true)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Queen(true)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Rook(true)
						|| if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == Bishop(true))
						{	
							$move->$valid = true;
						}
					}
			}
		
		

		$move->invalidReason = 'A King cannot make a move like this!';
	}
}
