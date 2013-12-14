<?php

/**
 * Represents a Queen chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Queen extends ChessPiece {
    
    /**
     * HTML's UTF-8 entitie for chess character
     * white Queen
     * @var     string
     */
    const UTF8_WHITE = '&#x2655;';
    
    /**
     * HTML's UTF-8 entitie for chess character
     * black Queen
     * @var     string
     */
    const UTF8_BLACK = '&#x265B;';
    
    /**
     * Chess notation letter for this chess piece (english)
     * White is upper case.
     * @var string
     */
    const LETTER_WHITE = 'Q';
    
    /**
     * Chess notation letter for this chess piece (english)
     * black is lower case.
     * @var string
     */
    const LETTER_BLACK = 'q';
    
    /**
     * Check if $move is a valid move for a Queen
     * and sets $move->valid and $move->invalidMessage accordingly
     * @param     Move     $move
     */
    public function validateMove(Move $move, Board $board) {
        // Valid move for a Queen:
        // The queen can be moved any number of unoccupied squares in 
        // a straight line vertically, 
        // horizontally, 
        // or diagonally, 
        // thus combining the moves of the Rook and Bishop.
        if  (  $move->getRankOffset() * $move->getFileOffset() != 0
            && abs($move->getRankOffset()) - abs($move->getFileOffset()) != 0
            ) {
            $move->setInvalid('chess.invalidmove.queen');
            return;
        }
        if (!$move->getPath()->isEmpty()) {
            $move->setInvalid('chess.invalidmove.blocked');
        }
    }
}
