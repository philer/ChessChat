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
     * white King object
     * @var King
     */
    protected $whiteKing = null;
    
    /**
     * black King object
     * @var King
     */
    protected $blackKing = null;
    
    /**
     * Move that has been executed on this Board
     * @var Move
     */
    protected $moved = null;
    
    /**
     * Game object that this Board belongs to
     * @var Game
     */
    protected $game = null;
    
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
    public function __construct(Game $game, $boardStr) {
        $this->game = $game;
        
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
            if ($boardStr[$cp+1] == 'x') {
                $this->whitePrison[] = ChessPiece::getInstance($boardStr[$cp]);
            
            } elseif ($boardStr[$cp+1] == 'y') {
                $this->blackPrison[] = ChessPiece::getInstance($boardStr[$cp]);
            
            } else {
                $cpObj = ChessPiece::getInstance(substr($boardStr, $cp, 3));
                switch (get_class($cpObj)) {
                    case 'Pawn':
                        $cpObj->canEnPassant = ctype_upper($boardStr[$cp+1]);
                        if ($cpObj->isWhite()) {
                            $this->whitePawns[] = $cpObj;
                        } else {
                            $this->blackPawns[] = $cpObj;
                        }
                        break;
                    case 'King':
                        if ($cpObj->isWhite()) {
                            $this->whiteKing = $cpObj;
                        } else {
                            $this->blackKing = $cpObj;
                        }
                    case 'Rook':
                        $cpObj->canCastle = ctype_upper($boardStr[$cp+1]);
                        break;
                }
                $this->board[ strtolower($boardStr[$cp+1]) ][ intval($boardStr[$cp+2]) ]
                    = $cpObj;
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
                $cp = $this->board[$file][$rank];
                if (!$cp->isEmpty()) {
                    $boardStr .= $cp->letter();
                    switch (get_class($cp)) {
                        case 'Pawn':
                            $boardStr .= $cp->canEnPassant ? strtoupper($file) : $file;
                            break;
                        case 'King':
                        case 'Rook':
                            $boardStr .= $cp->canCastle ? strtoupper($file) : $file;
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
     * Returns a copy of the specified Square on this board
     * Expects either
     * 1 String defining a square, such as 'A1' or
     * 1 Square object or
     * 2 parameters defining a square, such as 'A' and 1
     * Returns null if Square coordinates don't exist
     * @param  String/Square $p1
     * @param  int           $p2
     * @return Square
     */
    public function getSquare($p1, $p2 = null) {
        return clone $this->getSquareByReference($p1, $p2);
    }
    
    /**
     * Returns a reference to the specified Square on this board
     * Expects either
     * 1 String defining a square, such as 'A1' or
     * 1 Square object or
     * 2 parameters defining a square, such as 'A' and 1
     * Returns null if Square coordinates don't exist
     * @param  String/Square $p1
     * @param  int           $p2
     * @return Square
     */
    protected function &getSquareByReference($p1, $p2 = null) {
        if ($p1 instanceof Square) {
            $square = $p1;
        } else {
            $square = new Square($p1, $p2);
        }
        if ($square->exists()) {
            return $this->board[ $square->fileChar() ][ $square->rank() ];
        } else {
            throw new FatalException('trying to access nonexistant Square');
        }
    }
    
    /**
     * Returns the King object of specified color
     * @param  boolean $white
     * @return King
     */
    public function getKing($white) {
        return $white ? $this->whiteKing : $this->blackKing;
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
     * @param  Square $from
     * @param  mixed $to
     * @return Range
     */
    public function getRange(Square $from, $to, $exclusive = true) {
        return new Range($this, $from, $to, $exclusive);
    }
    
    /**
     * Determines whether or not the given Square is attacked by any of
     * the opponents ChessPieces.
     * $white may be omitted if $square is a ChessPiece.
     * @param  Square   $target
     * @param  boolean  $white   own color
     * @return boolean
     */
    public function underAttack(Square $target, $white = null) {
        if ($white === null) {
            $white = $target->isWhite();
        }
        
        return Pawn::underAttack($this, $target, $white)
            || Knight::underAttack($this, $target, $white)
            || Bishop::underAttack($this, $target, $white)
            || Rook::underAttack($this, $target, $white)
            || King::underAttack($this, $target, $white);
    }
    
    /**
     * Checks if King is in check
     * @param  boolean $white
     * @return boolean
     */
    public function inCheck($white) {
        return $this->underAttack(
            $white ? $this->whiteKing : $this->blackKing
        );
    }
    
    /**
     * Checks if King is in checkmate
     * @param  boolean $white
     * @return boolean
     */
    public function inCheckmate($white) {
        $king = $white ? $this->whiteKing : $this->blackKing;
        
        if ($king->canMove($this)) {
            return false;
        }
        
        $attackers = array();
        $attackPaths = array();
        
        foreach (Knight::getAttackRange($this, $king) as $square) {
            if (   $square instanceof Knight
                && $square->isWhite() != $white) {
                $attackers[] = $square;
            }
        }
        foreach (Pawn::getAttackRange($this, $king, $white) as $square) {
            if (   $square instanceof Pawn
                && $square->isWhite() != $white) {
                $attackers[] = $square;
            }
        }
        foreach (Bishop::getAttackRange($this, $king) as $range) {
            foreach ($range as $square) {
                if (!$square->isEmpty()) {
                    if (   $square->isWhite() != $white
                        && (   $square instanceof Bishop
                            || $square instanceof Queen)) {
                        $attackers[]   = $square;
                        $attackPaths[] = new Range($this, $square, $king);
                    } else {
                        break 1;
                    }
                }
            }
        }
        foreach (Rook::getAttackRange($this, $king) as $range) {
            foreach ($range as $square) {
                if (!$square->isEmpty()) {
                    if (   $square->isWhite() != $white
                        && (   $square instanceof Rook
                            || $square instanceof Queen)) {
                        $attackers[]   = $square;
                        $attackPaths[] = new Range($this, $square, $king);
                    } else {
                        break 1;
                    }
                }
            }
        }
        if (count($attackers) == 1) {
            if (   Pawn::underAttack($this, $attackers[0], !$white)
                || Knight::underAttack($this, $attackers[0], !$white)
                || Bishop::underAttack($this, $attackers[0], !$white)
                || Rook::underAttack($this, $attackers[0], !$white) ) {
                return false;
            } else {
                // can our King capture?
                return abs($king->file() - $attackers[0]->file()) > 1
                    || abs($king->rank() - $attackers[0]->rank()) > 1
                    || $this->underAttack($attackers[0], !$white);
            }
        } else {
            return count($attackers == 0); // just in case we aren't even in check
        }
    }
    
    /**
     * Executes given Move on this Board. Does not validate.
     * @param  Move   $move a valid move
     */
    public function move(Move $move) {
        $from = &$this->getSquareByReference($move->from);
        $to   = &$this->getSquareByReference($move->to);
        
        $from = new Square($move->from);
        
        if ($move->capture) {
            $this->capture($move->capture);
        }
        
        if ($move->promotion) {
            $to = clone $move->promotion;
        } else {
            $to = clone $move->from;
            $to->move($move->to);
        }
        
        if ($to instanceof King) {
            if ($to->isWhite()) {
                $this->whiteKing = $to;
            } else {
                $this->blackKing = $to;
            }
        }
        
        if ($move->castling) {
            $rookFrom = &$this->getSquareByReference($move->castling['from']);
            $rookTo   = &$this->getSquareByReference($move->castling['to']);
            $rookTo   = $rookFrom;
            $rookFrom = new Square($move->castling['from']);
            $rookTo->move($move->castling['to']);
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
            throw new FatalException('no Move to revert');
        }
        
        $from = &$this->getSquareByReference($move->from);
        $to   = &$this->getSquareByReference($move->to);
        $from = clone $move->from;
        $to   = clone $move->to;
        
        if ($from instanceof King) {
            if ($from->isWhite()) {
                $this->whiteKing = $from;
            } else {
                $this->blackKing = $from;
            }
        }
        
        if ($move->capture) {
            $this->uncapture($move->capture);
        }
        
        if ($move->castling) {
            $rookFrom = &$this->getSquareByReference($move->castling['from']);
            $rookTo   = &$this->getSquareByReference($move->castling['to']);
            $rookFrom = clone $move->castling['from'];
            $rookTo   = clone $move->castling['to'];
        }
        
        $this->moved = null;
    }
    
    /**
     * Capture a ChessPiece
     * @param  ChessPiece $captive
     */
    protected function capture(ChessPiece $captive) {
        if ($captive->isWhite()) {
            $this->whitePrison[] = $captive;
        } else {
            $this->blackPrison[] = $captive;
        }
        $captive = new Square($captive);
    }
    
    /**
     * Restores a ChessPiece that has been captured
     * @param  ChessPiece $captive
     */
    protected function uncapture(ChessPiece $captive) {
        $restore = $this->getSquareByReference($captive);
        if ($captive->isWhite()) {
            $resture = array_pop($this->whitePrison);
        } else {
            $resture = array_pop($this->blackPrison);
        }
    }
    
    /**
     * Finalize changes after executing a move. After this function has been
     * called, Board::revert() is no longer reliably possible.
     */
    public function cleanup() {
        $to = $this->getSquareByReference($this->moved->to);
        if ($to instanceof Rook || $to instanceof King) {
            $to->canCastle = false;
        }
        foreach ( ($this->game->whitesTurn() ? $this->whitePawns : $this->blackPawns) as $pawn ) {
            $pawn->canEnPassant = false;
        }
    }
}
