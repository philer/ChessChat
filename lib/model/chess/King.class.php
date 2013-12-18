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
            if (!$rookFrom instanceof Rook || !$rookFrom->canCastle) {
                $move->setInvalid('chess.invalidmove.castling');
                return;
            }
            if (!$board->getRange($move->from, $rookFrom)->isEmpty()) {
                $move->setInvalid('chess.invalidmove.blocked');
                return;
            }
            // TODO check check for King's path
            $move->castling['from'] = $rookFrom;
            $move->castling['to'] = new Square( $foff > 0 ? 'f' : 'd', $move->from->rank() );
        
        } elseif (abs($foff) > 1 || abs($move->getRankOffset()) > 1) {
            $move->setInvalid('chess.invalidmove.king');
            return;
        }
    }
    
    /**
     * Checks if King is in check.
     * @param  Board  $board
     * @return boolean
     */
    public function inCheck(Board $board) {
        return $board->underAttack($this);
    }
    
    public function inCheckmate(Board $board) {
        $checkmate = false;
        if (!$this->canMove($board)) {
            $checkmate = true;
            $paths = $board->getAttackPaths($this);
            if (count($paths) == 1) {
                echo $paths[0];
                foreach ($paths[0] as $square) {
                    if ($board->blockable($square, $this->white)) {
                        echo $square;
                        $checkmate = false;
                        break;
                    }
                }
            }
        }
        return $checkmate;
    }
    
    /**
     * Checks if player's King is able to move without stepping into check.
     * Does not check if the King is actually in check himself.
     * Check does not include castling.
     * @param  boolean  $white  which color to check
     * @return boolean
     */
    public function canMove(Board $board) {
        foreach (King::getAttackRange($board, $this) as $escape) {
            if ($escape->isEmpty() || $escape->isWhite() != $this->white) {
                // simulate
                $board->move(new Move($this, $escape));
                if (!$board->getKing($this->white)->inCheck($board)) {
                    $board->revert();
                    return true;
                }
                $board->revert();
            }
        }
        return false;
    }
    
    /**
     * Returns an array with all (existing) Squares that a King may
     * move to or come from.
     * Does not include castling moves.
     * @param   Board          $board
     * @param   Square         $position
     * @return  array<Square>
     */
    public static function getAttackRange(Board $board, Square $position) {
        $squares = array();
        for ( $f=-1 ; $f<=1 ; $f++ ) {
            for ( $r=-1 ; $r<=1 ; $r++ ) {
                if (!($f==0 && $r==0)) {
                    $square = new Square(
                        $position->file() + $f,
                        $position->rank() + $r
                    );
                    if ($square->exists()) {
                        $squares[] = $board->getSquare($square);
                    }
                    
                }
            }
        }
        return $squares;
    }
    
    /**
     * Checks whether $target Square is under Attack by opponents King
     * Does not care if the Square is actually covered by one of our own
     * @param  Board   $board
     * @param  Square  $target
     * @param  boolean $white
     * @return boolean
     */
    public static function underAttack(Board $board, Square $target, $white) {
        // see if opponents King is close enough
        $oppKing = $board->getKing(!$white);
        return abs($oppKing->file() - $target->file()) <= 1 && abs($oppKing->rank() - $target->rank()) <= 1;
    }
    
    public static function getAttackPaths(Board $board, Square $target, $white) {
        $squares = array();
        foreach (King::getAttackRange($board, $target) as $square) {
            if (   $square instanceof King
                && $square->isWhite() != $white) {
                $squares[] = $square;
            }
        }
        return $squares;
    }
}
