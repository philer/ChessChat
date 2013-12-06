<?php

/**
 * Represents one field on a chess board and provides methods for easy access.
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
     * On a Square may stand a ChessPiece
     * @var ChessPiece
     */
    public $chesspiece = null;
    
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
    public function __construct($file, $rank = null, Chesspiece $chesspiece = null) {
        $this->chesspiece = $chesspiece;
        
        if (is_int($file)) {
            // two int
            $this->file = $file;
            $this->rank = (int) $rank;
        
        } elseif (is_string($file)) {
            if (strlen($file) == 1) {
                // one char one int
                $this->file = ord(strtolower($file)) - ord('a');
                $this->rank = (int) $rank;
            
            } else {
                if (strlen($file) == 3) {
                    $this->chesspiece = ChessPiece::getInstance($file[0]);
                    $file = substr($file, 1);
                }
                // string like 'a4' or '4A'
                if (is_numeric($file[0])) {
                    $this->rank = intval($file[0]);
                    $this->file = ord(strtolower($file[1])) - ord('a');
                } else {
                    $this->rank = intval($file[1]);
                    $this->file = ord(strtolower($file[0])) - ord('a');
                }
            }
        } else {
            throw new Exception('Expecting parameter 1 to be either int or string');
        }
    }
    
    /**
     * Equals method that only cares about coordinates (not ChessPieces).
     * @param  Square $square
     * @return boolean
     */
    public function equals(Square $square) {
        return $this->file == $square->file() && $this->rank == $square->rank();
    }
    
    /**
     * Returns unified string representation of a Square,
     * like 'A1'
     * @return string
     */
    public function __toString() {
        return $this->fileCapital() . $this->rank();
    }
    
    /**
     * Returns lower case letter of this Square's file (column)
     * @see    Square::fileCapital()
     * @return String
     */
    public function fileChar() {
        return chr($this->file + ord('a'));
    }
    
    /**
     * Returns upper case letter of this Square's file (column)
     * @see    Square::fileChar()
     * @return String
     */
    public function fileCapital() {
        return chr($this->file + ord('A'));
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
     * Whether or not there is a ChessPiece standing on this Square
     * @return boolean
     */
    public function isEmpty() {
        return $this->chesspiece == null;
    }
}
