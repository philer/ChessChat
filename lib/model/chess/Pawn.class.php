<?php

/**
 * Represents a Pawn chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Pawn extends ChessPiece {
    
    /**
     * Indicates if this Pawn may be captured en passant,
     * which is the case right after it took a double step.
     * @var boolean
     */
    public $canEnPassant = false;

    /**
     * HTML's UTF-8 entitie for chess character
     * white Pawn
     * @var     string
     */
    const UTF8_WHITE = '&#x2659;';
    
    /**
     * HTML's UTF-8 entitie for chess character
     * black Pawn
     * @var     string
     */
    const UTF8_BLACK = '&#x265F;';
    
    /**
     * Chess notation letter for this chess piece (english)
     * White is upper case.
     * @var string
     */
    const LETTER_WHITE = 'P';
    
    /**
     * Chess notation letter for this chess piece (english)
     * black is lower case.
     * @var string
     */
    const LETTER_BLACK = 'p';
    
    /**
     * Check if $move is a valid move for a Pawn
     * and sets $move->valid and $move->invalidMessage accordingly.
     * @param     Move     $move
     */
    public function validateMove(Move $move, Board $board) {
        // Valid move for Pawn:
        // does not move backwards
        // normally advancing a single square
        // the first time a pawn is moved, it has the option of advancing two squares
        // Pawns may not use the initial two-square advance to jump over an occupied square, or to capture.
        // A pawn captures diagonally, one square forward and to the left or right. 
        
        $roff = $move->getRankOffset();
        $foff = $move->getFileOffset();
        
        if ($this->white) {
            if  ($roff < 1 || $roff > 2) {
                $move->setInvalid('chess.invalidmove.pawn');
                return;
            }
            if ($roff == 2) {
            
                if ($this->rank() != 2) {
                    $move->setInvalid('chess.invalidmove.pawn');
                    return;
                }
                if (!$board->getSquare($this->file(), 3)->isEmpty()) {
                    $move->setInvalid('chess.invalidmove.blocked');
                    return;
                }
                // opponents pawns may capture en passant next move
                $this->canEnPassant = true;
            }
            if ($move->to->rank() == 8
                &&  (  is_null($move->promotion)
                    || $move->promotion instanceof Pawn
                    || $move->promotion instanceof King
                    )
                ) {
                $move->setInvalid('chess.invalidmove.promotion');
                return;
            }
        } else {
            if  ($roff > -1 || $roff < -2) {
            
                $move->setInvalid('chess.invalidmove.pawn');
                return;
            
            } elseif ($roff == -2) {
            
                if ($this->rank() != 7) {
                    $move->setInvalid('chess.invalidmove.pawn');
                    return;
                }
                if (!$board->getSquare($this->file(), 6)->isEmpty()) {
                    $move->setInvalid('chess.invalidmove.blocked');
                    return;
                }
                // opponents pawns may capture en passant next move
                $this->canEnPassant = true;
            }
            if  (   $move->to->rank() == 1
                &&  (  is_null($move->promotion)
                    || $move->promotion instanceof Pawn
                    || $move->promotion instanceof King
                    )
                ) {
                $move->setInvalid('chess.invalidmove.promotion');
                return;
            }
        }
        
        if (abs($foff) > 1) {
            $move->setInvalid('chess.invalidmove.pawn');
            return;
            
        } elseif (abs($foff) == 1) {
            // capture
            if (abs($roff) == 1) {
                if ($move->to->isEmpty()) {
                    // en passant
                    $target = $board->getSquare($move->to->file(), $this->rank());
                    if (   $target->isEmpty()
                        || !$target->canEnPassant
                        || $target->isWhite() == $this->isWhite()
                        ) {
                        $move->setInvalid('chess.invalidmove.pawn.nocapture');
                        return;
                    }
                    $move->capture = $target;
                    return;
                
                } elseif ($move->to->isWhite() == $this->isWhite()) {
                    $move->setInvalid('chess.invalidmove.owncolor');
                    return;
                }
            } else {
                $move->setInvalid('chess.invalidmove.pawn');
                return;
            }
        } elseif (!$move->to->isEmpty()) {
            // pawn can only capture diagonally
            $move->setInvalid('chess.invalidmove.blocked');
            return;
        }
    }
    
    public function canMove(Board $board) {
        foreach (self::getAttackRange($board, $this, 4) as $to) {
            if ($to->isEmpty() || $to->isWhite() == $this->white) {
                continue;
            } else {
                // simulate
                $board->move(new Move($this, $to));
                if (!$board->inCheck($this->white)) {
                    $board->revert();
                    return true;
                }
                $board->revert();
            }
        }
        if ($this->white) {
            $to = $board->getSquare($this->file, $this->rank + 1);
            if ($to->isEmpty()) {
                // simulate
                $board->move(new Move($this, $to));
                if (!$board->inCheck($this->white)) {
                    $board->revert();
                    return true;
                }
                $board->revert();
            }
            if ($this->rank == 2) {
                $to = $board->getSquare($this->file, $this->rank + 2);
                if ($to->isEmpty()) {
                    // simulate
                    $board->move(new Move($this, $to));
                    if (!$board->inCheck($this->white)) {
                        $board->revert();
                        return true;
                    }
                    $board->revert();
                }
            }
        } else {
            $to = $board->getSquare($this->file, $this->rank - 1);
            if ($to->isEmpty()) {
                // simulate
                $board->move(new Move($this, $to));
                if (!$board->inCheck($this->white)) {
                    $board->revert();
                    return true;
                }
                $board->revert();
            }
            if ($this->rank == 7) {
                $to = $board->getSquare($this->file, $this->rank - 2);
                if ($to->isEmpty()) {
                    // simulate
                    $board->move(new Move($this, $to));
                    if (!$board->inCheck($this->white)) {
                        $board->revert();
                        return true;
                    }
                    $board->revert();
                }
            }
        }
        return false;
    }
    
    /**
     * Determine all Squares that a Pawn on $position may attack or from
     * which a Pawn may attack $position.
     * @param  Board   $board
     * @param  Square  $position
     * @param  boolean $white     may be omitted if $position holds a chesspiece
     * @return array<Square>
     */
    public static function getAttackRange(Board $board, Square $position, $white) {
        // this also works the other way round, to check if a piece may
        // be attacked by a pawn of the opposite color. See Board::inCheck()
        $roff = $white ? 1 : -1;
        $emptySquares = array(
            new Square($position->file() - 1, $position->rank() + $roff),
            new Square($position->file() + 1, $position->rank() + $roff)
        );
        $squares = array();
        foreach ($emptySquares as $square) {
            if ($square->exists()) {
                $squares[] = $board->getSquare($square);
            }
        }
        return $squares;
    }
    
    public static function underAttack(Board $board, Square $target, $white) {
        foreach (Pawn::getAttackRange($board, $target, $white) as $square) {
            if (   $square instanceof Pawn
                && $square->isWhite() != $white) {
                return true;
            }
        }
        // en passant
        if ($target instanceof Pawn && $target->canEnPassant) {
            $ghost = new Square($target->file(), $target->rank() + ($target->white ? -1 : 1) );
            foreach (Pawn::getAttackRange($board, $ghost, $white) as $square) {
                if (   $square instanceof Pawn
                    && $square->isWhite() != $white) {
                    return true;
                }
            }
        }
        return false;
    }
    
    /**
     * Pawn may put himself in harms way without capturing
     * @param  Board    $board
     * @param  Square   $target
     * @param  boolean  $white
     * @return boolean
     */
    public static function blockable(Board $board, Square $target, $white) {
        if ($white) {
            $block = $board->getSquare($target->file(), $target->rank() - 1);
            if ($block instanceof Pawn && $block->isWhite()) {
                return true;
            }
            if ($target->rank() == 4 && $block->isEmpty()) {
                $block = $board->getSquare($target->file(), $target->rank() - 2);
                if ($block instanceof Pawn && $block->isWhite()) {
                    return true;
                }
            }
        
        } else {
            $block = $board->getSquare($target->file(), $target->rank() + 1);
            if ($block instanceof Pawn && !$block->isWhite()) {
                return true;
            }
            if ($target->rank() == 5 && $block->isEmpty()) {
                $block = $board->getSquare($target->file(), $target->rank() + 2);
                if ($block instanceof Pawn && !$block->isWhite()) {
                    return true;
                }
            }
        }
        return false;
    }
}
