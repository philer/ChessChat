<?php

/**
 * Represents a Knight chess piece.
 * @author Philipp Miller, Larissa Hammerstein
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
	 * Valid move for a Knight: 
	 * move to a square that is two squares horizontally and one square vertically, 
	 * or two squares vertically and one square horizontally
	 * can jump over other pieces
	 */
	public function validateMove(Move &$move) {
		$move->valid = false;
		if(abs($letterToIndex($move[0])-$letterToIndex($move[3])) == 1 && abs($numberToIndex($move[1])-$numberToIndex($move[4])) == 2
		|| abs($letterToIndex($move[0])-$letterToIndex($move[3])) == 2 && abs($numberToIndex($move[1])-$numberToIndex($move[4])) == 1)
		{
			if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == null ){
				$move->$valid = true;
				}
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Knight(true) )	{
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
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Knight(false) )	{
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
		

		 $move->invalidReason = 'A Knight cannot make a move like this';
	}
}
