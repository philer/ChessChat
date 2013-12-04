<?php

class Square {
    
    protected $file = 0;
    
    protected $rank = 0;
    
    public $chesspiece = null;
    
    /**
     * Valid coordinates must follow this pattern
     */
    const PATTERN = '([a-hA-H][1-8]|[1-8][a-hA-H])';
    
    public function __construct($file, $rank = null, Chesspiece $chesspiece = null) {
        if (is_int($file)) {
            // two int
            $this->file = $file;
            $this->rank = (int) $rank;
        
        } elseif (strlen($file) == 1) {
            // one char one int
            $this->file = ord(strtolower($file)) - ord('a');
            $this->rank = (int) $rank;
        
        } else {
            // string like 'a4' or '4A'
            if (is_numeric($file[0])) {
                $this->rank = intval($file[0]);
                $this->file = ord(strtolower($file[1])) - ord('a');
            } else {
                $this->rank = intval($file[1]);
                $this->file = ord(strtolower($file[0])) - ord('a');
            }
        }
        $this->chesspiece = $chesspiece;
    }
    
    public function __toString() {
        return $this->fileCapital() . $this->rank();
    }
    
    public function fileChar() {
        return chr($this->file + ord('a'));
    }
    
    public function fileCapital() {
        return chr($this->file + ord('A'));
    }
    
    public function file() {
        return $this->file;
    }
    
    public function rank() {
        return $this->rank;
    }
    
    public function isEmpty() {
        return $this->chesspiece == null;
    }
}
