<?php

/**
 * Represents a Rook chess piece.
 * @author Philipp Miller, Larissa Hammerstein
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
	 * Valid move for a Rook:
	 * The rook moves horizontally or vertically, 
	 * through any number of unoccupied squares
	 */
	public function validateMove(Move &$move) {
		$move->valid = false;
		if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == null ){
					$move->$valid = true;
				}
				
		elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Rook(true) )	{
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
				
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Rook(false) ){
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
		if($move[0] == $move[3]]){

			for($i=min($move[1] , $move[4]) ; $i < max($move[1] , $move[4]) ; $i++){
				if($array[$letterToIndex($move[0]-1)][$numberToIndex(i)] != null){
					$move->valid = false;
					}
				}
				
			}
		elseif($move[1] == $move[4]]){
			$move->valid = true;
			for($i=min($move[0] , $move[3]) ; $i < max($move[0] , $move[3]) ; $i++){
				if($array[$letterToIndex(i)][$move[1]-1] != null){
					$move->valid = false;
					}
				}
			}
			
			
		$move->invalidReason = 'A Rook cannot make a move like this';
	}
}
