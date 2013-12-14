<?php

/**
 * Represents a Range of Squares on a chess Board.
 * Ranges are either vertical, horizontal or diagonal.
 * Implements Iterator for easy traversal via foreach.
 * 
 * @see Iterator
 * @author  Philipp Miller
 */
class Range implements Iterator {
    
    /**
     * If $board is set this range will return Squares from given Board.
     * @var Board
     */
    protected $board = null;
    
    /**
     * Square indicating the starting point of this Range.
     * Will _not_ be included in this Range.
     * @var Square
     */
    protected $start = null;
    
    /**
     * Current Square used in iteration
     * @var Square
     */
    protected $current = null;
    
    /**
     * Square indicating the end of this Range.
     * Will _not_ be included in this Range.
     * @var Square
     */
    protected $end = null;
    
    /**
     * Current offset for interation
     * @var integer
     */
    protected $offset = 0;
    
    /**
     * Indicates in which horizontal direction this
     * Range reaches.
     * @var integer  -1, 0 or 1
     */
    protected $fileDirection = 0;
    
    /**
     * Indicates in which vertical direction this
     * Range reaches.
     * @var integer  -1, 0 or 1
     */
    protected $rankDirection = 0;
    
    /**
     * Use this to create a diagonal range.
     */
    const TOP_LEFT     = 0;
    
    /**
     * Use this to create a vertical range.
     */
    const TOP          = 1;
    
    /**
     * Use this to create a diagonal range.
     */
    const TOP_RIGHT    = 2;
    
    /**
     * Use this to create a horizontal range.
     */
    const RIGHT        = 3;
    
    /**
     * Use this to create a diagonal range.
     */
    const BOTTOM_RIGHT = 4;
    
    /**
     * Use this to create a vertical range.
     */
    const BOTTOM       = 5;
    
    /**
     * Use this to create a diagonal range.
     */
    const BOTTOM_LEFT  = 6;
    
    /**
     * Use this to create a horizontal range.
     */
    const LEFT         = 7;
    
    /**
     * Creates a new Range from start to end.
     * Start and end are excluded!
     * 
     * $end my be either a Square or a direction constant.
     * If $end is a direction constant, the Range will reach
     * to the edge of the board in the given direction.
     * 
     * If $board is provided, current will return the respective
     * Square on $board.
     * 
     * @param  Square $start
     * @param  mixed  $end
     * @param  Board  $board
     */
    public function __construct(Square $start, $end, Board $board = null) {
        $this->start = $start;
        $this->board = $board;
        
        if ($end instanceof Square) {
            $this->end = $end;
            $this->fileDirection = Util::sign( $end->file() - $start->file() );
            $this->rankDirection = Util::sign( $end->rank() - $start->rank() );
        } else {
            switch ($end) {
                case self::TOP:
                case self::TOP_RIGHT:
                case self::TOP_LEFT:
                    $this->rankDirection = 1;
                    break;
                case self::BOTTOM:
                case self::BOTTOM_RIGHT:
                case self::BOTTOM_LEFT:
                    $this->rankDirection = -1;
                    break;
                case self::RIGHT:
                case self::LEFT:
                    $this->rankDirection = 0;
                    break;
                default:
                    throw new Exception('no direction specified');
            }
            switch ($end) {
                case self::RIGHT:
                case self::TOP_RIGHT:
                case self::BOTTOM_RIGHT:
                    $this->fileDirection = 1;
                    break;
                case self::LEFT:
                case self::TOP_LEFT:
                case self::BOTTOM_LEFT:
                    $this->fileDirection = -1;
                    break;
                case self::TOP:
                case self::BOTTOM:
                    $this->fileDirection = 0;
                    break;
            }
        }
        $this->rewind();
    }
    
    /**
     * @see Iterator::rewind() 
     */
    public function rewind() {
        $this->offset = 0;
        $this->next();
    }
    
    /**
     * @see Iterator::current()
     * @return Square
     */
    public function current() {
        return $this->current;
    }
    
    /**
     * @see Iterator::key()
     * @return integer  index starting at 0
     */
    public function key() {
        return $this->offset - 1;
    }
    
    /**
     * @see Iterator::next()
     */
    public function next() {
        $this->offset++;
        $square = new Square(
            $this->start->file() + $this->offset * $this->fileDirection,
            $this->start->rank() + $this->offset * $this->rankDirection
        );
        if (!is_null($this->board) && $square->exists()) {
            $this->current = $this->board->getSquare($square);
        } else {
            $this->current = $square;
        }
    }
    
    /**
     * @see Iterator::valid()
     * @return boolean
     */
    public function valid() {
        return $this->current->exists()
            && ( is_null($this->end) || !$this->current->equals($this->end) );
    }
    
    /**
     * Determines whether all Squares in this Range are empty
     * i.E. if there are no ChessPieces.
     * @return boolean
     */
    public function isEmpty() {
        foreach ($this as $square) {
            if (!$square->isEmpty()) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Ranges have a simple string representation.
     * Mainly useful for debugging.
     * @return string
     */
    public function __toString() {
        $string = '';
        foreach ($this as $square) {
            $string .= (string) $square . ',';
        }
        return '{' . rtrim($string, ',') . '}';
    }
    
    /**
     * In case you need this as an array for some reason.
     * If you miss a function in here, it is recommended
     * to implement it instead of converting to array.
     * @return array<Square>
     */
    public function toArray() {
        $array = array();
        foreach ($this as $entry) {
            $array[] = $entry;
        }
        return $array;
    }
}
