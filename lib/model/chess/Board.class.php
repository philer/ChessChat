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
    public function getRange(Square $from, $to) {
        return new Range($from, $to, $this);
    }
    
    /**
     * Checks if player's King is in check.
     * If no Square is provided defaults to specified
     * color's King on this Board.
     * @param  boolean  $white
     * @param  Square   $square
     * @return
     */
    public function inCheck($white, Square $square = null) {
        if ($square instanceof Square) {
            $kingSquare = $square;
        } else {
            $kingSquare = clone ($white ? $this->whiteKingSquare : $this->blackKingSquare);
        }
        
        foreach (Pawn::getAttackRange($kingSquare, $this) as $square) {
            if (   $square->chesspiece instanceof Pawn
                && $square->chesspiece->isWhite() != $white) {
                return true;
            }
        }
        foreach (Knight::getAttackRange($kingSquare, $this) as $square) {
            if (   $square->chesspiece instanceof Knight
                && $square->chesspiece->isWhite() != $white) {
                return true;
            }
        }
        foreach (Rook::getAttackRange($kingSquare, $this) as $range) {
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
        foreach (Bishop::getAttackRange($kingSquare, $this) as $range) {
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
     * Checks if player's King is able to move without stepping into check.
     * @param  boolean  $white  which color to check
     * @return boolean
     */
    public function inCheckmate($white) {
        $kingSquare = $white ? $this->whiteKingSquare : $this->blackKingSquare;
        foreach (King::getAttackRange($kingSquare, $this) as $escape) {
            if ($escape->isEmpty() || $escape->chesspiece->isWhite() != $white) {
                $escape->chesspiece = new King ($white); // temporary King
                if (!$this->inCheck($white, $escape)) {
                    return false;
                }
            }
        }
        return true;
    }
    
    /**
     * Execute given Move on this Board. Does not validate.
     * @param  Move   $move a valid move
     */
    public function move(Move $move) {
        $this->capture($move->capture);
        
        $this->board[$move->from->fileChar()][$move->from->rank()]->chesspiece = null;
        $toPiece = &$this->board[$move->to->fileChar()][$move->to->rank()]->chesspiece;
        
        if ($move->promotion) {
            $toPiece = $move->promotion;
        } else {
            $toPiece = $move->from->chesspiece;
        }
        if (!empty($move->castling)) {
            $this->board[$move->castling['from']->fileChar()][$move->castling['from']->rank()]
                ->chesspiece = null;
            $this->board[$move->castling['to']->fileChar()][$move->castling['to']->rank()]
                ->chesspiece = new Rook($toPiece->isWhite(), false);
        }
        if ($toPiece instanceof Rook || $toPiece instanceof King) {
            $toPiece->canCastle = false;
        }
        $this->clearEnPassant(!$move->from->chesspiece->isWhite());
    }
    
    public function revert(Move $move) {
        // TODO
    }
    
    /**
     * Executes a capture on the given Square if
     * it contains a ChessPiece, return false otherwise.
     * @param   Square   $square
     * @return  boolean  successful capture
     */
    public function capture(Square $target) {
        // get reference
        $target = $this->getSquare($target);
        if (is_null($target->chesspiece)) {
            return false;
        }
        if ($target->chesspiece->isWhite()) {
            $this->whitePrison[] = $target->chesspiece;
        } else {
            $this->blackPrison[] = $target->chesspiece;
        }
        $target->chesspiece = null;
        return true;
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
