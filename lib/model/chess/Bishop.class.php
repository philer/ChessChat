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
    
    /**
     * Whether or not this Bishop can Move on given Board
     * @param  Board  $board
     * @return boolean
     */
    public function canMove(Board $board) {
        foreach (self::getAttackRange($board, $this) as $range) {
            foreach ($range as $to) {
                if ($to->isEmpty() || $to->isWhite() != $this->white) {
                    // simulate
                    $board->move(new Move($this, $to));
                    if (!$board->inCheck($this->white)) {
                        $board->revert();
                        return true;
                    }
                    $board->revert();
                    if (!$to->isEmpty()) {
                        continue 2;
                    }
                } else {
                    continue 2;
                }
            }
        }
        return false;
    }
    
    /**
     * Get all Squares that a Bishop at $position may move to or from
     * @param  Board  $board
     * @param  Square $position
     * @return array<Ranges>
     */
    public static function getAttackRange(Board $board, Square $position) {
        $ranges = array();
        foreach (array(Range::TOP_LEFT, Range::TOP_RIGHT, Range::BOTTOM_RIGHT, Range::BOTTOM_LEFT) as $direction) {
            $ranges[] = new Range($board, $position, $direction);
        }
        return $ranges;
    }
    
    /**
     * Whether or not a Bishop attacks $target Square
     * @param  Board    $board
     * @param  Square   $target
     * @param  boolean  $white
     * @return boolean
     */
    public static function underAttack(Board $board, Square $target, $white) {
        foreach (self::getAttackRange($board, $target) as $range) {
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
    
    /**
     * Bishops move over an diagonal line of Squares when they attack
     * This function returns these Squares, excluding start and end
     * @param  Board    $board
     * @param  Square   $target
     * @param  boolean  $white
     * @return array<Range>
     */
    public static function getAttackPaths(Board $board, Square $target, $white) {
        $paths = array();
        foreach (self::getAttackRange($board, $target) as $range) {
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
