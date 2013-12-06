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
    
    public function __construct($white, $canEnPassant = false) {
        parent::__construct($white);
        $this->canEnPassant = $canEnPassant;
    }
    
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
            
            } elseif ($roff == 2) {
            
                if ($move->from->rank() != 2) {
                    $move->setInvalid('chess.invalidmove.pawn');
                    return;
                }
                if (!$board->getSquare($move->from->file(), 3)->isEmpty()) {
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
            
                if ($move->from->rank() != 7) {
                    $move->setInvalid('chess.invalidmove.pawn');
                    return;
                }
                if (!$board->getSquare($move->from->file(), 6)->isEmpty()) {
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
                    $target = $board->getSquare($move->to->file(), $move->from->rank());
                    if (   $target->isEmpty()
                        || !$target->chesspiece->canEnPassant
                        || $target->chesspiece->isWhite() == $move->from->chesspiece->isWhite()
                        ) {
                        $move->setInvalid('chess.invalidmove.pawn.nocapture');
                        return;
                    }
                    $move->capture = $target;
                    return;
                
                } elseif ($move->to->chesspiece->isWhite() == $move->from->chesspiece->isWhite()) {
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
}
