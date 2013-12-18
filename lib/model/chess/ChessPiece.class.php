<?php

/**
 * Represents a single chess piece
 * @author Philipp Miller
 */
abstract class ChessPiece extends Square {
    
    /**
     * Color of this ChessPiece as boolean:
     * white = true, black = false
     * Does not indicate the Square's color!
     * @var     boolean
     */
    protected $white = true;
    
    /**
     * A ChessPiece (letter) may be identified by this pattern
     */
    const PATTERN = '([bpnrkqBPNRKQ]{1})';
    
    /**
     * Creates a Square from given parameters.
     * Expects either
     * 1 String defining a square, such as 'A1' or
     * 1 character and 1 integer defining a square, such as 'a' and 1 or
     * 2 integers defining a square, such as 0 and 1
     * An optional ChessPiece may be provided to represent it standing on the Square
     * @param String/integer $file
     * @param integer        $rank
     * @param ChessPiece     $chesspiece
     */
    public function __construct($white, $p2 = null, $p3 = null) {
        $this->white = $white;
        if (!is_null($p2)) {
            parent::__construct($p2, $p3);
        }
    }
    
    /**
     * Returns a new ChessPiece. Type is specified by the first character in $string
     * Coordinates are optional
     * @param  string  $string
     * @param  mixed   $square  Square or valid coordinates as string
     * @return ChessPiece
     */
    public static function getInstance($string, $square = null) {
        $letter = $string[0];
        if (is_null($square) && strlen($string) == 3) {
            $square = substr($string, 1);
        }
        switch(strtolower($letter)) {
            case Pawn::LETTER_BLACK :   return new Pawn(  ctype_upper($letter), $square);
            case Bishop::LETTER_BLACK : return new Bishop(ctype_upper($letter), $square);
            case Knight::LETTER_BLACK : return new Knight(ctype_upper($letter), $square);
            case Rook::LETTER_BLACK :   return new Rook(  ctype_upper($letter), $square);
            case Queen::LETTER_BLACK :  return new Queen( ctype_upper($letter), $square);
            case King::LETTER_BLACK :   return new King(  ctype_upper($letter), $square);
        }
    }
    
    /**
     * Checks if $move is a valid move for this chess piece
     * and sets $move->valid and $move->invalidMessage accordingly.
     * @param     Move     $move
     */
    abstract public function validateMove(Move $move, Board $board);
    
    /**
     * First letter indicates type, followed by coordinates
     * @return string
     */
    public function __toString() {
        return $this->letter() . $this->coordinates();
    }
    
    /**
     * Returns this ChessPiece's utf-8 html entity.
     * @return  string  utf8 chess symbol
     */
    public function utf8() {
        return $this->white ? static::UTF8_WHITE : static::UTF8_BLACK;
    }

    /**
     * Returns this ChessPiece's letter in chess notation.
     * Capital letter represents white.
     * @return     string
     */
    public function letter() {
        return $this->white ? static::LETTER_WHITE : static::LETTER_BLACK;
    }
    
    /**
     * Is this a white chesspiece?
     * @return     boolean
     */
    public function isWhite() {
        return $this->white;
    }
    
    /**
     * Returns a json encoded (string) representation of this Move's relevant
     * information for use in ajax response
     * @return array
     */
    public function ajaxData() {
        return array(
            // 'name'   => get_class($this), // use langvar
            'letter' => $this->letter(),
            'utf8'   => $this->utf8()
        );
    }
}
