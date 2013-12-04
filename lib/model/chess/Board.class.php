<?php

class Board {
    
    protected $board = array();
    
    protected $whitePrison = array(); // TODO
    
    protected $blackPrison = array(); // TODO
    
    /**
     * Chessboard is represented as a string for easy transmission and storage.
     * Conventions:
     * - 3 characters per piece. (3*32 = 96 total)
     * - first character: piece type, capital for white
     * - second character: file (column)
     *      + capital for king means no castling yet
     *      + capital for pawn means he just did a double step, which is
     *        relevant for en passant (update it after next move!)
     * - third character: rank (row)
     * - files 'v' and 'w' for dead white pieces, files 'x' and 'y' for dead black pieces
     * TODO(- first chesspiece indicates who's turn it is (redundant with status))
     * 
     * @var string
     */
    const DEFAULT_STRING = 'RA1Nb1Bc1Qd1KE1Bf1Ng1RH1Pa2Pb2Pc2Pd2Pe2Pf2Pg2Ph2pa7pb7pc7pd7pe7pf7pg7ph7rA8nb8bc8qd8kE8bf8ng8rH8';
    
    
    public function __construct($boardStr) {
        $this->board = array();
        for ( $file='a' ; $file<='h' ; $file++ ) {
            $this->board[$file] = array();
            for ( $rank=1; $rank<=8 ; $rank++ ) {
                $this->board[$file][$rank] = new Square($file, $rank);
            }
        }
        $this->board['x'] = array(); // TODO
        $this->board['y'] = array(); // TODO
        
        for ( $cp=0 ; $cp<96 ; $cp+=3 ) {
            $cpObj = ChessPiece::getInstance($boardStr[$cp]);
            if ($cpObj instanceof Pawn) {
                $cpObj->canEnPassant = ctype_upper($boardStr[$cp+1]);
            } elseif ($cpObj instanceof Rook || $cpObj instanceof King) {
                $cpObj->canCastle = ctype_upper($boardStr[$cp+1]);
            }
            $this->board[ strtolower($boardStr[$cp+1]) ][ hexdec($boardStr[$cp+2]) ]->chesspiece = $cpObj;
        }
    }
    
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
        // TODO
        foreach ($this->board['x'] as $i => $cp) $boardStr .= $cp->letter() . 'x' . dechex($i);
        foreach ($this->board['y'] as $i => $cp) $boardStr .= $cp->letter() . 'y' . dechex($i);
        return $boardStr;
    }
    
    public function __get($squareString) {
        return $this->getSquare($squareString);
    }
    
    public function getSquare($p1, $p2 = null) {
        if ($p1 instanceof Square) $square = $p1;
        else $square = new Square($p1, $p2);
        return $this->board[ $square->fileChar() ][ $square->rank() ];
    }
    
    public function getWhitePrison() {
        return $this->whitePrison;
    }
    
    public function getBlackPrison() {
        return $this->blackPrison;
    }
    
    public function range(Square $from, Square $to) {
        $range = array();
        if ($from->file() == $to->file()) {
            $minRank = min($from->rank(), $to->rank()) + 1;
            $maxRank = max($from->rank(), $to->rank()) - 1;
            for ( $r=$minRank ; $r<=$maxRank ; $r++ ) {
                $range[] = $this->board[$from->fileChar()][$r];
            }
        } elseif ($from->rank() == $to->rank()) {
            $minFile = chr(min($from->file(), $to->file()) + 1 + ord('a'));
            $maxFile = chr(max($from->file(), $to->file()) - 1 + ord('a'));
            for ( $f=$minFile ; $f<=$maxFile ; $f++ ) {
                $range[] = $this->board[$f][$from->rank()];
            }
        } else {
            $ltr = $from->file() < $to->file();
            $offset = abs($from->rank() - $to->rank()) - 2;
            $minRank = min($from->rank(), $to->rank()) + 1;
            $minFile = min($from->file(), $to->file()) + 1;
            for ( $i=0 ; $i<=$offset ; $i++ ) {
                $range[] = $this->board[chr(($ltr ? $i : $offset-$i) + $minFile + ord('a'))][$i + $minRank];
            }
        }
        return $range;
    }
    
    public function move(Move $move) {
        // if (!$move->to->isEmpty()) ; // TODO capture
        // $move->to->chesspiece = $move->from->chesspiece;
        // $move->from->chesspiece = null;
        $this->board[$move->to->fileChar()][$move->to->rank()]->chesspiece = $move->from->chesspiece;
        $this->board[$move->from->fileChar()][$move->from->rank()]->chesspiece = null;
    }
}
