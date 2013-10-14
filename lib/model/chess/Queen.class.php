<?php

/**
 * Represents a Queen chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Queen extends ChessPiece {
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * white Queen
	 * @var 	string
	 */
	const UTF8_WHITE = '&#x2655;';
	
	/**
	 * HTML's UTF-8 entitie for chess character
	 * black Queen
	 * @var 	string
	 */
	const UTF8_BLACK = '&#x265B;';
	
	/**
	 * Check if $move is a valid move for a Queen
	 * and sets $move->valid and $move->invalidMessage accordingly
	 * @param 	Move 	$move
	 * Valid move for a Queen:
	 * The queen can be moved any number of unoccupied squares in 
	 * a straight line vertically, 
	 * horizontally, 
	 * or diagonally, 
	 * thus combining the moves of the Rook and Bishop. 
	 */
	public function validateMove(Move &$move) {
		$move->valid = false;
		//moving like a Rook
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
			
			
		// $move->invalidReason = 'You Suck!';
	
		//moving like a Bishop
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

		$move->invalidReason = 'A Queen cannot make a move like this!';
	}
}
