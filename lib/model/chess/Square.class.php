<?php

/**
 * Represents one field on a chess board and provides methods for easy access.
 * Note, that Squares do _not_ have a color.
 * @author  Philipp Miller
 */
class Square {
    
    /**
     * File is a column on a chess board,
     * generally represented by a letter from A to H.
     * Internal representation is an integer from 0 to 7.
     * @var integer
     */
    protected $file = 0;
    
    /**
     * Rank is a row on a chess board,
     * represented by a digit from 1 to 8.
     * @var integer
     */
    protected $rank = 0;
    
    /**
     * Valid coordinates must follow this pattern
     */
    const PATTERN = '([a-hA-H][1-8]|[1-8][a-hA-H])';
    
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
    public function __construct($p1, $p2 = null) {
        if ($p1 instanceof Square) {
            $this->file = $p1->file();
            $this->rank = $p1->rank();
        
        } elseif (is_int($p1)) {
            // two int
            $this->file = $p1;
            $this->rank = (int) $p2;
        
        } elseif (is_string($p1)) {
            if (strlen($p1) == 1) {
                // one char one int
                $this->file = ord(strtolower($p1)) - ord('a');
                $this->rank = (int) $p2;
            
            } else {
                // string like 'a4' or '4A'
                if (is_numeric($p1[0])) {
                    $this->rank = intval($p1[0]);
                    $this->file = ord(strtolower($p1[1])) - ord('a');
                } else {
                    $this->rank = hexdec($p1[1]); // might be in prison
                    $this->file = ord(strtolower($p1[0])) - ord('a');
                }
            }
        
        } else {
            throw new FatalException('Expecting parameter 1 to be either int or string');
        }
    }
    
    /**
     * Equals method that only cares about coordinates (not ChessPieces).
     * Returns false if $square is null.
     * @param  Square $square
     * @return boolean
     */
    public function equals($square) {
        return $square instanceof Square
            && $this->file == $square->file()
            && $this->rank == $square->rank();
    }
    
    /**
     * @see  Square::coordinates()
     * @return string
     */
    public function __toString() {
        return $this->coordinates();
    }
    
    /**
     * Returns unified string representation of a Square,
     * like 'A1'
     * @return string
     */
    public function coordinates() {
        return $this->fileCapital() . $this->rank();
    }
    
    /**
     * Returns lower case letter of this Square's file (column)
     * @see    Square::fileCapital()
     * @return String
     */
    public function fileChar() {
        return chr( $this->file + ord('a') );
    }
    
    /**
     * Returns upper case letter of this Square's file (column)
     * @see    Square::fileChar()
     * @return String
     */
    public function fileCapital() {
        return chr( $this->file + ord('A') );
    }
    
    /**
     * Returns this Square's file (column) represented as an integer
     * @see    Square::$file
     * @return integer
     */
    public function file() {
        return $this->file;
    }
    
    /**
     * Returns this Square's rank (row)
     * @see    Square::$rank
     * @return integer
     */
    public function rank() {
        return $this->rank;
    }
    
    /**
     * Checks whether this Square's coordinates exist on a chess board.
     * @return  boolean
     */
    public function exists() {
        return $this->file >= 0
            && $this->file <= 7
            && $this->rank >= 1
            && $this->rank <= 8;
    }
    
    /**
     * Whether or not there is a ChessPiece standing on this Square
     * @return boolean
     */
    public function isEmpty() {
        return !($this instanceof ChessPiece);
    }
    
    /**
     * Replaces this Square's coordinates with given
     * Square's coordinates
     * @param  Square $square
     */
    public function move(Square $square) {
        $this->file = $square->file();
        $this->rank = $square->rank();
    }
}
