<?php

/**
 * Represents a chess board and provides methods for easy access and manipulation.
 * @author  Philipp Miller
 */
class Board {
    
    /**
     * Array contains this boards data
     * @var array<Square>
     */
    protected $board = array();
    
    /**
     * Prison owned by white player (contains black chess pieces)
     * @var array<ChessPiece>
     */
    protected $whitePrison = array();
    
    /**
     * Prison owned by black player (contains white chess pieces)
     * @var array<ChessPiece>
     */
    protected $blackPrison = array();
    
    /**
     * Holds references to all active (non captured) white Pawns for easy access.
     * @var array<Pawn>
     */
    protected $whitePawns = array();
    
    /**
     * Holds references to all active (non captured) black Pawns for easy access.
     * @var array<Pawn>
     */
    protected $blackPawns = array();
    
    /**
     * Location of the white King.
     * References the King's Square in $board array
     * @var Square
     */
    protected $whiteKingSquare = null;
    
    /**
     * Location of the black King.
     * References the King's Square in $board array
     * @var Square
     */
    protected $blackKingSquare = null;
    
    /**
     * Move that has been executed on this Board
     * @var Move
     */
    protected $moved = null;
    
    /**
     * A chessboard is represented as a string for easy transmission and storage.
     * Conventions:
     * - 3 characters per piece. (3*32 = 96 total)
     * - first character: piece type, capital for white
     * - second character: file (column)
     *      + capital for king and rook means he hasn't moved yet, can do castling
     *      + capital for pawn means he just did a double step, can be captured en passant
     * - third character: rank (row)
     * - file 'x' for dead white pieces, file 'y' for dead black pieces
     *      + rank in hex to allow one digit counting 16 pieces
     * 
     * @var string
     */
    const DEFAULT_STRING = 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8';
    
    /**
     * Creates a new Board from a givin string
     * @see   Board::DEFAULT_STRING
     * @param string $boardStr
     */
    public function __construct($boardStr) {
        $this->board = array();
        for ( $file='a' ; $file<='h' ; $file++ ) {
            $this->board[$file] = array();
            for ( $rank=1; $rank<=8 ; $rank++ ) {
                $this->board[$file][$rank] = new Square($file, $rank);
            }
        }
        $this->whitePrison = array();
        $this->blackPrison = array();
        
        for ( $cp=0 ; $cp<96 ; $cp+=3 ) {
            $cpObj = ChessPiece::getInstance($boardStr[$cp]);
            
            if ($boardStr[$cp+1] == 'x') {
                $this->whitePrison[] = $cpObj;
            
            } elseif ($boardStr[$cp+1] == 'y') {
                $this->blackPrison[] = $cpObj;
            
            } else {
                $square = &$this->board[ strtolower($boardStr[$cp+1]) ][ intval($boardStr[$cp+2]) ];
                if ($cpObj instanceof Pawn) {
                    $cpObj->canEnPassant = ctype_upper($boardStr[$cp+1]);
                    if ($cpObj->isWhite()) {
                        $this->whitePawns[] = $cpObj;
                    } else {
                        $this->blackPawns[] = $cpObj;
                    }
                } elseif ($cpObj instanceof Rook) {
                    $cpObj->canCastle = ctype_upper($boardStr[$cp+1]);
                } elseif ($cpObj instanceof King) {
                    $cpObj->canCastle = ctype_upper($boardStr[$cp+1]);
                    if ($cpObj->isWhite()) {
                        $this->whiteKingSquare = $square;
                    } else {
                        $this->blackKingSquare = $square;
                    }
                }
                $square->chesspiece = $cpObj;
            }
        }
    }
    
    /**
     * Renders this board's string representation
     * @see    Board::DEFAULT_STRING
     * @return string
     */
    public function __toString() {
        $boardStr = '';
        for ( $file='a' ; $file<='h' ; $file++ ) {
            for ( $rank=1; $rank<=8 ; $rank++ ) {
                $square = $this->board[$file][$rank];
                if (!$square->isEmpty()) {
                    $boardStr .= $square->chesspiece->letter();
                    switch (get_class($square->chesspiece)) {
                        case 'Pawn' :
                            $boardStr .= $square->chesspiece->canEnPassant ? strtoupper($file) : $file;
                            break;
                        case 'King' :
                        case 'Rook' :
                            $boardStr .= $square->chesspiece->canCastle ? strtoupper($file) : $file;
                            break;
                        default :
                            $boardStr .= $file;
                            break;
                    }
                    $boardStr .= $rank;
                }
            }
        }
        foreach ($this->whitePrison as $i => $cp) $boardStr .= $cp->letter() . 'x' . dechex($i);
        foreach ($this->blackPrison as $i => $cp) $boardStr .= $cp->letter() . 'y' . dechex($i);
        return $boardStr;
    }
    
    /**
     * Convenience method for accessing squares on this board. 
     * Syntax: $board->{'A1'}
     * @param  string/Square $squareString
     * @return Square
     */
    public function __get($squareString) {
        return $this->getSquare($squareString);
    }
    
    /**
     * Returns a copy of the specified Square from this board.
     * Expects either
     * 1 String defining a square, such as 'A1' or
     * 1 Square object or
     * 2 parameters defining a square, such as 'A' and 1
     * Returns null if Square coordinates don't exist
     * @see    Square::__construct()
     * @param  String/Square $p1
     * @param  int           $p2
     * @return Square
     */
    public function getSquare($p1, $p2 = null) {
        if ($p1 instanceof Square) {
            $square = $p1;
        } else {
            $square = new Square($p1, $p2);
        }
        if ($square->exists()) {
            return clone $this->board[ $square->fileChar() ][ $square->rank() ];
        } else {
            return null;
        }
    }
    
    protected function getSquareByReference(Square $square) {
        return $this->board[ $square->fileChar() ][ $square->rank() ];
    }
    
    public function getKingSquare($white) {
        return $white ? $this->whiteKingSquare : $this->blackKingSquare;
    }
    
    /**
     * Returns this boards white prison
     * @see    whitePrison
     * @return array
     */
    public function getWhitePrison() {
        return $this->whitePrison;
    }
    
    /**
     * Returns this boards black prison
     * @see    blackPrison
     * @return array
     */
    public function getBlackPrison() {
        return $this->blackPrison;
    }
    
    /**
     * Returns an array containing all squares between
     * the two Squares $from and $to (excluding $from and $to).
     * Works horizontally, vertically and diagonally.
     * @see  Range::__construct()
     * 
     * @param  Square $from
     * @param  mixed $to
     * @return Range
     */
    public function getRange(Square $from, $to, $exclusive = true) {
        return new Range($this, $from, $to, $exclusive);
    }
    
    /**
     * Checks if player's King is in check.
     * If no Square is provided defaults to specified
     * color's King on this Board.
     * @param  boolean  $white
     * @param  Square   $square
     * @return boolean
     */
    public function inCheck($white, Square $kingSquare = null) {
        if ($kingSquare instanceof Square) {
            return $this->underAttack($kingSquare, $white);
        } else {
            return $this->underAttack($white ? $this->whiteKingSquare : $this->blackKingSquare, $white);
        }
    }
    
    /**
     * Checks if player's King is able to move without stepping into check.
     * Does not check if the King is actually in check himself!
     * @param  boolean  $white  which color to check
     * @return boolean
     */
    public function kingCanMove($white) {
        $kingSquare = $white ? $this->whiteKingSquare : $this->blackKingSquare;
        foreach (King::getAttackRange($this, $kingSquare) as $escape) {
            if (($escape->isEmpty() || $escape->chesspiece->isWhite() != $white)
                && !$this->underAttack($escape, $white)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Determines whether or not the given Square is attack.
     * $white may be omitted if $square does hold a ChessPiece.
     * @param  Square   $target
     * @param  boolean  $white   own color
     * @return boolean
     */
    public function underAttack(Square $target, $white = null) {
        if ($white === null) {
            $white = $target->chesspiece->isWhite();
        }
        
        foreach (Pawn::getAttackRange($this, $target, $white) as $square) {
            if (   $square->chesspiece instanceof Pawn
                && $square->chesspiece->isWhite() != $white) {
                return true;
            }
        }
        foreach (Knight::getAttackRange($this, $target) as $square) {
            if (   $square->chesspiece instanceof Knight
                && $square->chesspiece->isWhite() != $white) {
                return true;
            }
        }
        foreach (King::getAttackRange($this, $target) as $square) {
            if (   $square->chesspiece instanceof King
                && $square->chesspiece->isWhite() != $white) {
                return true;
            }
        }
        foreach (Rook::getAttackRange($this, $target) as $range) {
            foreach ($range as $square) {
                if (!$square->isEmpty()) {
                    if (   $square->chesspiece->isWhite() != $white
                        && (   $square->chesspiece instanceof Rook
                            || $square->chesspiece instanceof Queen)) {
                        return true;
                    } else {
                        break 1;
                    }
                }
            }
        }
        foreach (Bishop::getAttackRange($this, $target) as $range) {
            foreach ($range as $square) {
                if (!$square->isEmpty()) {
                    if (   $square->chesspiece->isWhite() != $white
                        && (   $square->chesspiece instanceof Bishop
                            || $square->chesspiece instanceof Queen)) {
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
     * Get all Paths on which an opponent's ChessPiece may reach the given Square.
     * Returns a mixed Array containing Ranges and another array with single Squares.
     * $white may be omitted if $target contains a ChessPiece.
     * @param  Square  $target
     * @param  boolean $white
     * @return array
     */
    public function getAttackPaths(Square $target, $white = null) {
        if ($white === null) {
            $white = $target->chesspiece->isWhite();
        }
        
        $paths = array();
        $paths[0] = array($target); // single squares
        foreach (Pawn::getAttackRange($this, $target, $white) as $square) {
            if (   $square->chesspiece instanceof Pawn
                && $square->chesspiece->isWhite() != $white) {
                $paths[0][] = $square;
            }
        }
        foreach (Knight::getAttackRange($this, $target) as $square) {
            if (   $square->chesspiece instanceof Knight
                && $square->chesspiece->isWhite() != $white) {
                $paths[0][] = $square;
            }
        }
        foreach (King::getAttackRange($this, $target) as $square) {
            if (   $square->chesspiece instanceof King
                && $square->chesspiece->isWhite() != $white) {
                $paths[0][] = $square;
            }
        }
        foreach (Rook::getAttackRange($this, $target) as $range) {
            foreach ($range as $square) {
                if (!$square->isEmpty()) {
                    if (   $square->chesspiece->isWhite() != $white
                        && (   $square->chesspiece instanceof Rook
                            || $square->chesspiece instanceof Queen)) {
                        $paths[] = new Range($this, $square, $target, false);
                    } else {
                        break 1;
                    }
                }
            }
        }
        foreach (Bishop::getAttackRange($this, $target) as $range) {
            foreach ($range as $square) {
                if (!$square->isEmpty()) {
                    if (   $square->chesspiece->isWhite() != $white
                        && (   $square->chesspiece instanceof Bishop
                            || $square->chesspiece instanceof Queen)) {
                        $paths[] = new Range($this, $square, $target, false);
                    } else {
                        break 1;
                    }
                }
            }
        }
        return $paths;
    }
    
    /**
     * Execute given Move on this Board. Does not validate.
     * @param  Move   $move a valid move
     */
    public function move(Move $move) {
        
        if (!$move->capture->isEmpty()) {
            $capture = $this->getSquareByReference($move->capture);
            if ($capture->chesspiece->isWhite()) {
                $this->whitePrison[] = $capture->chesspiece;
            } else {
                $this->blackPrison[] = $capture->chesspiece;
            }
            $capture->chesspiece = null;
        }
        
        $this->getSquareByReference($move->from)->chesspiece = null;
        
        $to = $this->getSquareByReference($move->to);
        if ($move->promotion) {
            $to->chesspiece = clone $move->promotion;
        } else {
            $to->chesspiece = clone $move->from->chesspiece;
        }
        
        if ($to->chesspiece instanceof King) {
            if ($to->chesspiece->isWhite()) {
                $this->whiteKingSquare = $to;
            } else {
                $this->blackKingSquare = $to;
            }
        }
        
        if (!empty($move->castling)) {
            $this->getSquareByReference($move->castling['from'])->chesspiece = null;
            $this->getSquareByReference($move->castling['to'])->chesspiece = 
                new Rook($to->chesspiece->isWhite(), false);
        }
        
        $this->moved = $move;
    }
    
    /**
     * Reverts a Move that was executed on this Board.
     * This is no longer reliably possible once Board::cleanup()
     * has been called.
     */
    public function revert() {
        if ($this->moved) {
            $move = $this->moved;
        } else {
            throw new Exception('no Move to revert');
        }
        
        $this->getSquareByReference($move->from)->chesspiece = clone $move->from->chesspiece;
        $this->getSquareByReference($move->to)->chesspiece   = null;
        
        if (!$move->capture->isEmpty()) {
            $capture = $this->getSquareByReference($move->capture);
            if ($move->capture->chesspiece->isWhite()) {
                $capture->chesspiece = array_pop($this->whitePrison);
            } else {
                $capture->chesspiece = array_pop($this->blackPrison);
            }
        }
        
        if (!empty($move->castling)) {
            $this->getSquareByReference($move->castling['from'])->chesspiece = 
                new Rook($toPiece->isWhite(), true);
            $this->getSquareByReference($move->castling['to'])->chesspiece = null;
        }
        
        $this->moved = null;
    }
    
    /**
     * Finalize changes after executing a move. After this function has been
     * called, Board::revert() is no longer reliably possible.
     */
    public function cleanup() {
        $toPiece = &$this->getSquareByReference($this->moved->to)->chesspiece;
        if ($toPiece instanceof Rook || $toPiece instanceof King) {
            $toPiece->canCastle = false;
        }
        $this->clearEnPassant(!$this->moved->from->chesspiece->isWhite());
    }
    
    /**
     * Sets all own Pawn's $canEnPassant flags to false.
     * $white determines which color is 'own'. 
     * @param  boolean  $white
     */
    public function clearEnPassant($white) {
        foreach ( ($white ? $this->whitePawns : $this->blackPawns) as $pawn ) {
            $pawn->canEnPassant = false;
        }
    }
}
