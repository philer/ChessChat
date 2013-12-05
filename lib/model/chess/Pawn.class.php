<?php

/**
 * Represents a Pawn chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class Pawn extends ChessPiece {
    
    /**
     * Pawn's may sometimes move 'en passant'
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
                if ($move->from->file() > 0) {
                    $oppPawn = $board->getSquare($move->from->file() - 1, 4)->chesspiece;
                    if ($oppPawn instanceof Pawn) $oppPawn->canEnPassant = true;
                }
                if ($move->from->file() < 7) {
                    $oppPawn = $board->getSquare($move->from->file() + 1, 4)->chesspiece;
                    if ($oppPawn instanceof Pawn) $oppPawn->canEnPassant = true;
                }
            }
        } else {
            if  ($roff > -1 || $roff < -2) {
            
                $move->setInvalid('chess.invalidmove.pawn');
            
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
                if ($move->from->file() > 0) {
                    $oppPawn = $board->getSquare($move->from->file() - 1, 5)->chesspiece;
                    if ($oppPawn instanceof Pawn) $oppPawn->canEnPassant = true;
                }
                if ($move->from->file() < 7) {
                    $oppPawn = $board->getSquare($move->from->file() + 1, 5)->chesspiece;
                    if ($oppPawn instanceof Pawn) $oppPawn->canEnPassant = true;
                }
            }
        }
        
        if (abs($foff) > 1) {
            $move->setInvalid('chess.invalidmove.pawn');
            
        } elseif (abs($foff) == 1) {
            //attempted capture
            if (abs($roff) == 1) {
                if ($this->canEnPassant) {
                    // note: if you can move en passant there can't be a piece at $move->to
                    $target = clone $board->getSquare($move->from->file() + $foff, $move->from->rank());
                } else {
                    $target = $move->to;
                }
                if (is_null($target->chesspiece)) {
                    $move->setInvalid('chess.invalidmove.pawn.nocapture');
                } elseif ($target->chesspiece->isWhite() == $move->from->chesspiece->isWhite()) {
                    $move->setInvalid('chess.invalidmove.owncolor');
                } else {
                    $move->target = $target;
                }
            } else {
                $move->setInvalid('chess.invalidmove.pawn');
            }
        } elseif (!is_null($move->to->chesspiece)) {
            // pawn can only capture diagonally
            $move->setInvalid('chess.invalidmove.blocked');
        }
    }
}
