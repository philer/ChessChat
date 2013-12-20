<?php

/**
 * Represents a Rook chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Rook extends ChessPiece {
    
    /**
     * King may only perform castling if the Rook hasn't been moved before.
     * @var boolean
     */
    public $canCastle = false;
    
    /**
     * HTML's UTF-8 entitie for chess character
     * white Rook
     * @var     string
     */
    const UTF8_WHITE = '&#x2656;';
    
    /**
     * HTML's UTF-8 entitie for chess character
     * black Rook
     * @var     string
     */
    const UTF8_BLACK = '&#x265C;';
    
    /**
     * Chess notation letter for this chess piece (english)
     * White is upper case.
     * @var string
     */
    const LETTER_WHITE = 'R';
    
    /**
     * Chess notation letter for this chess piece (english)
     * black is lower case.
     * @var string
     */
    const LETTER_BLACK = 'r';
    
    /**
     * Check if $move is a valid move for a Rook
     * and sets $move->valid and $move->invalidMessage accordingly.
     * @param     Move     $move
     */
    public function validateMove(Move $move, Board $board) {
         // Valid move for a Rook:
         // The rook moves horizontally or vertically, 
         // through any number of unoccupied squares
        if ($move->getRankOffset() * $move->getFileOffset() != 0) {
            $move->setInvalid('chess.invalidmove.rook');
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
     * Whether or not a Rook attacks $target Square
     * @param  Board    $board
     * @param  Square   $target
     * @param  boolean  $white
     * @return boolean
     */
    public static function getAttackRange(Board $board, Square $position) {
        $ranges = array();
        foreach (array(Range::TOP, Range::RIGHT, Range::BOTTOM, Range::LEFT) as $direction) {
            $ranges[] = new Range($board, $position, $direction);
        }
        return $ranges;
    }
    
    /**
     * Rooks move over a straight line of Squares when they attack
     * This function returns these Squares, excluding start and end
     * @param  Board    $board
     * @param  Square   $target
     * @param  boolean  $white
     * @return array<Range>
     */
    public static function underAttack(Board $board, Square $target, $white) {
        foreach (self::getAttackRange($board, $target) as $range) {
            foreach ($range as $square) {
                if (!$square->isEmpty()) {
                    if (   $square->isWhite() != $white
                        && (   $square instanceof Rook
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
     * Rooks move over an straight line of Squares when they attack
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
                        && (   $square instanceof Rook
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
