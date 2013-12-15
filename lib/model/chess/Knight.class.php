<?php

/**
 * Represents a Knight chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Knight extends ChessPiece {
    
    /**
     * HTML's UTF-8 entitie for chess character
     * white Knight
     * @var     string
     */
    const UTF8_WHITE = '&#x2658;';
    
    /**
     * HTML's UTF-8 entitie for chess character
     * black Knight
     * @var     string
     */
    const UTF8_BLACK = '&#x265E;';
    
    /**
     * Chess notation letter for this chess piece (english)
     * White is upper case.
     * @var string
     */
    const LETTER_WHITE = 'N';
    
    /**
     * Chess notation letter for this chess piece (english)
     * black is lower case.
     * @var string
     */
    const LETTER_BLACK = 'n';
    
    /**
     * Check if $move is a valid move for a Knight
     * and sets $move->valid and $move->invalidMessage accordingly.
     * @param     Move     $move
     */
    public function validateMove(Move $move, Board $board) {
        // Valid move for a Knight: 
        // move to a square that is two squares horizontally and one square vertically, 
        // or two squares vertically and one square horizontally
        // can jump over other pieces
        if ( abs($move->getRankOffset() * $move->getFileOffset()) != 2 ) {
            $move->setInvalid('chess.invalidmove.knight');
        }
    }
    
    public static function getAttackRange(Board $board, Square $position) {
        $emptySquares = array(
            new Square($position->file() -1, $position->rank() +2),
            new Square($position->file() +1, $position->rank() +2),
            new Square($position->file() +2, $position->rank() +1),
            new Square($position->file() +2, $position->rank() -1),
            new Square($position->file() +1, $position->rank() -2),
            new Square($position->file() -1, $position->rank() -2),
            new Square($position->file() -2, $position->rank() -1),
            new Square($position->file() -2, $position->rank() +1),
        );
        $squares = array();
        foreach ($emptySquares as $square) {
            if ($square->exists()) {
                $squares[] = $board->getSquare($square);
            }
        }
        return $squares;
    }
}
