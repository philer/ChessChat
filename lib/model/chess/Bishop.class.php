<?php

/**
 * Represents a Bishop chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Bishop extends ChessPiece {
        
    /**
     * HTML's UTF-8 entitie for chess character
     * white Bishop
     * @var string
     */
    const UTF8_WHITE = '&#x2657;';
    
    /**
     * HTML's UTF-8 entitie for chess character
     * black Bishop
     * @var string
     */
    const UTF8_BLACK = '&#x265D;';
    
    /**
     * Chess notation letter for this chess piece (english)
     * White is upper case.
     * @var string
     */
    const LETTER_WHITE = 'B';
    
    /**
     * Chess notation letter for this chess piece (english)
     * black is lower case.
     * @var string
     */
    const LETTER_BLACK = 'b';
    
    /**
     * Check if $move is a valid move for a Bishop
     * and sets $move->valid and $move->invalidMessage accordingly
     * @param     Move     $move
     */
    public function validateMove(Move $move, Board $board) {
        // Valid move for a Bishop:
        // diagonal movement
        // no limits in distance
        // cannot jump over other pieces
        if (abs($move->getRankOffset()) != abs($move->getFileOffset())) {
            $move->setInvalid('chess.invalidmove.bishop');
            return;
        }
        if (!$move->getPath()->isEmpty()) {
            $move->setInvalid('chess.invalidmove.blocked');
        }
    }
    
    public static function getAttackRange(Board $board, Square $position) {
        $ranges = array();
        foreach (array(Range::TOP_LEFT, Range::TOP_RIGHT, Range::BOTTOM_RIGHT, Range::BOTTOM_LEFT) as $direction) {
            $ranges[] = new Range($board, $position, $direction);
        }
        return $ranges;
    }
    
    public static function underAttack(Board $board, Square $target, $white) {
        foreach (Bishop::getAttackRange($board, $target) as $range) {
            foreach ($range as $square) {
                if (!$square->isEmpty()) {
                    if (   $square->isWhite() != $white
                        && (   $square instanceof Bishop
                            || $square instanceof Queen)) {
                        return true;
                    } else {
                        break 1;
                    }
                }
            }
        }
        return false;
    }
    
    public static function getAttackPaths(Board $board, Square $target, $white) {
        $paths = array();
        foreach (Bishop::getAttackRange($board, $target) as $range) {
            foreach ($range as $square) {
                if (!$square->isEmpty()) {
                    if (   $square->isWhite() != $white
                        && (   $square instanceof Bishop
                            || $square instanceof Queen)) {
                        $paths[] = new Range($board, $square, $target);
                    } else {
                        break 1;
                    }
                }
            }
        }
        return $paths;
    }
}
