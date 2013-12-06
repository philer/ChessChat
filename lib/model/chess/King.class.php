<?php

/**
 * Represents a King chess piece.
 * @author Philipp Miller, Larissa Hammerstein
 */
class King extends ChessPiece {
    
    /**
     * King may only perform castling if he hasn't been moved before.
     * @var boolean
     */
    public $canCastle = false;
    
    /**
     * HTML's UTF-8 entitie for chess character
     * white King
     * @var     string
     */
    const UTF8_WHITE = '&#x2654;';
    
    /**
     * HTML's UTF-8 entitie for chess character
     * black King
     * @var     string
     */
    const UTF8_BLACK = '&#x265A;';
    
    /**
     * Chess notation letter for this chess piece (english)
     * White is upper case.
     * @var string
     */
    const LETTER_WHITE = 'K';
    
    /**
     * Chess notation letter for this chess piece (english)
     * black is lower case.
     * @var string
     */
    const LETTER_BLACK = 'k';
    
    public function __construct($white, $canCastle = false) {
        parent::__construct($white);
        $this->canCastle = $canCastle;
    }
    
    /**
     * Check if $move is a valid move for a King
     * and sets $move->valid and $move->invalidMessage accordingly.
     * @param     Move     $move
     */
    public function validateMove(Move $move, Board $board) {
        $foff = $move->getFileOffset();
        if ($this->canCastle && abs($foff) == 2) {
            // TODO check check
            $rookFrom = $board->getSquare( $foff > 0 ? 'h' : 'a', $move->from->rank() );
            if (!$rookFrom->chesspiece instanceof Rook || !$rookFrom->chesspiece->canCastle) {
                $move->setInvalid('chess.invalidmove.castling');
                return;
            }
            $obstacles = array_filter(
                $board->range($move->from, $rookFrom),
                function($square) { return !$square->isEmpty(); }
            );
            if (!empty($obstacles)) {
                $move->setInvalid('chess.invalidmove.blocked');
                return;
            }
            // TODO check check for King's path
            $move->castling['from'] = $rookFrom;
            $move->castling['to'] = $board->getSquare( $foff > 0 ? 'f' : 'd', $move->from->rank() );
        
        } elseif (abs($foff) > 1 || abs($move->getRankOffset()) > 1) {
            $move->setInvalid('chess.invalidmove.king');
            return;
        }
    }
}
