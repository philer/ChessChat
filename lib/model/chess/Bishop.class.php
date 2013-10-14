<?php

/**
 * Represents a Bishop chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Bishop extends ChessPiece {
		
	/**
	 * HTML's UTF-8 entitie for chess character
	 * white Bishop
	 * @var 	string
	 */
	const UTF8_WHITE = '&#x2657;';
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * black Bishop
	 * @var 	string
	 */
	const UTF8_BLACK = '&#x265D;';
	
	/**
	 * Check if $move is a valid move for a Bishop
	 * and sets $move->valid and $move->invalidMessage accordingly
	 * @param 	Move 	$move
	 * Valid move for a Bishop:
	 * diagonal movement
	 * no limits in distance
	 * cannot jump over other pieces
	 */
	public function validateMove(Move &$move) {
		$move->valid = false;
		if(abs($letterToIndex($move[0])-$letterToIndex($move[3])) - abs($numberToIndex($move[1])-$numberToIndex($move[4])==0){
			if($letterToIndex($move[0])>$letterToIndex($move[3]) && $letterToIndex($move[1])>$letterToIndex($move[4])){
				if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == null ){
					$move->$valid = true;
				}
				
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Bishop(true) )	{
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
				
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Bishop(false) )	{
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
				
				for($i = $letterToIndex($move[3])+1 ; $i < $letterToIndex($move[0]) ; i++){
					for($j = $letterToIndex($move[4])+1; $j < $letterToIndex($move[1] ; j++)){
						if($array[$letterToIndex($move[i])][$numberToIndex($move[j])] != null ){
							$move->valid = false;
							}
						}
					}
				}
	
				
			elseif($letterToIndex($move[0])>$letterToIndex($move[3]) && $letterToIndex($move[1])<$letterToIndex($move[4])){
				if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == null ){
				$move->$valid = true;
				}
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Bishop(true) )	{
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
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Bishop(false) )	{
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
				
				for($i = $letterToIndex($move[3])+1 ; $i < $letterToIndex($move[0]) ; i++){
					for($j = $letterToIndex($move[1])+1; $j < $letterToIndex($move[4] ; j++)){
						if($array[$letterToIndex($move[i])][$numberToIndex($move[j])] != null ){
							$move->valid = false;
							}
						}
					}
				}
			
				
		elseif($letterToIndex($move[0])<$letterToIndex($move[3]) && $letterToIndex($move[1])>$letterToIndex($move[4])){
				if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == null ){
				$move->$valid = true;
				}
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Bishop(true) )	{
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
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Bishop(false) )	{
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
				
				for($i = $letterToIndex($move[0])+1 ; $i < $letterToIndex($move[3]) ; i++){
					for($j = $letterToIndex($move[4])+1; $j < $letterToIndex($move[1] ; j++)){
						if($array[$letterToIndex($move[i])][$numberToIndex($move[j])] != null ){
							$move->valid = false;
							}
						}
					}
				}
				
				
		elseif($letterToIndex($move[0])<$letterToIndex($move[3]) && $letterToIndex($move[1])<$letterToIndex($move[4])){
				if($array[$letterToIndex($move[3])][$numberToIndex($move[4])] == null ){
				$move->$valid = true;
				}
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Bishop(true) )	{
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
			elseif($array[$letterToIndex($move[0])][$numberToIndex($move[1])] == Bishop(false) )	{
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
				
				for($i = $letterToIndex($move[0])+1 ; $i < $letterToIndex($move[3]) ; i++){
					for($j = $letterToIndex($move[1])+1; $j < $letterToIndex($move[4] ; j++)){
						if($array[$letterToIndex($move[i])][$numberToIndex($move[j])] != null ){
							$move->valid = false;
							}
						}
					}
				}
		}

		 $move->invalidReason = 'A Bishop cannot make a move like this!';
	}
}
