<?php

/**
 * Represents a chess move
 * @author Philipp Miller, Larissa Hammerstein
 */
class Move extends DatabaseModel {
    
    /**
     * Anything has and Id nowadays
     * @var integer
     */
    public $moveId = 0;
    
    /**
     * userId of User who made/tried this move.
     * @var integer
     */
    public $playerId = 0;
    
    /**
     * Square where we start
     * @var Square
     */
    public $from = null;
    
    /**
     * Square where we arrive
     * @var Square
     */
    public $to = null;
    
    /**
     * A Square containing the ChessPiece that may be captured by this move.
     * (May differ from $this->to if captured en passant)
     * @var Square
     */
    public $capture = null;
    
    /**
     * A ChessPiece that resulted from a Pawn promotion
     * @var ChessPiece
     */
    public $promotion = null;
    
    /**
     * Array containing castling Rook move (from and to)
     * @var array<Square>
     */
    public $castling = array();
    
    /**
     * Once the move has been checked it will be flagged as (not) valid.
     * @var boolean
     */
    protected $valid = true;
    
    /**
     * If the move has been flagged as invalid this message
     * should explain why. (hint: use language variables)
     * @var string
     */
    protected $invalidReason = '';
    
    /**
     * Current game
     * @var Game
     */
    protected $game = null;
    
    /**
     * Seperator for coordinate notation
     */
    const SEPERATOR_PATTERN = '[_ -]';
    
    /**
     * Creates a Move object from the given string.
     * Expects parameter 1 to be either an array containing data to be set
     * or a valid (unformatted) move string such as 'a4-3A'.
     */
    public function __construct($p1, $p2 = null) {
        
        if ($p1 instanceof Square && $p2 instanceof Square) {
            // simulated move
            
            $this->from = $p1;
            $this->to   = $p2;
            if (!$this->to->isEmpty()) {
                $this->capture = $this->to;
            }
            
        } elseif (is_string($p1) && self::patternMatch($p1) && $p2 instanceof Game) {
            // user move
            
            $this->game = $p2;
            
            $p1 = preg_replace('@' . self::SEPERATOR_PATTERN . '@', '', $p1);
            
            $this->from = $this->game->board->{ $p1[0] . $p1[1] };
            $this->to   = $this->game->board->{ $p1[2] . $p1[3] };
            
            if (!$this->to->isEmpty()) {
                $this->capture = $this->to;
            }
            
            if (strlen($p1) == 5) {
                $callback = $this->game->whitesTurn() ? 'strtoupper' : 'strtolower';
                $this->promotion = ChessPiece::getInstance($callback($p1[4]), $this->to);
            }
            
            $this->validate();
            
        } elseif (is_array($p1)) {
            // database move
            
            $this->from = ChessPiece::getInstance($p1['chessPiece'], $p1['fromSquare']);
            
            $this->to = new Square($p1['toSquare']);
            
            if ($p1['capture']) {
                $this->capture = ChessPiece::getInstance($p1['capture']);
            }
            if ($this->to->equals($this->capture)) {
                $this->to = $this->capture;
            }
            
            // promotion
            if ($p1['promotion']) {
                $this->promotion = ChessPiece::getInstance($p1['promotion'], $this->to);
            }
            
            // castling
            $foff = $this->getFileOffset();
            if ($this->from instanceof King && abs($foff) == 2 ) {
                $this->castling['from'] = new Square(
                    $foff > 0 ? 'h' : 'a',
                    $this->from->rank()
                );
                $this->castling['to']   = new Rook(
                    $this->from->isWhite(),
                    $foff > 0 ? 'f' : 'd',
                    $this->from->rank()
                );
            }
            
            // other data
            unset($p1['fromSqare'], $p1['toSquare'], $p1['capture'], $p1['promotion']);
            parent::__construct($p1);
            
        } else {
            throw new FatalException('invalid Arguments');
        }
    }
    
    /**
     * When treated as string a Move object will
     * return it's system formatted string representation
     * such as 'A4-A3'
     * @return     string
     */
    public function __toString() {
        return $this->from . '-' . $this->to;
    }
    
    /**
     * Validates this move by setting it's $valid and $invalidReason field accordingly.
     * Does basic validation by itself, then initiates piece specific validation.
     */
    public function validate() {
        if ($this->game->isOver()) {
            $this->setInvalid('chess.invalidmove.gameover');
        } elseif (Core::getUser()->getId() != $this->game->getCurrentPlayer()->getId()) {
            $this->setInvalid('chess.invalidmove.notyourturn');
        } elseif ($this->from->isEmpty()) {
            $this->setInvalid('chess.invalidmove.nopiece');
        } elseif (!$this->to->isEmpty() && $this->from->isWhite() == $this->to->isWhite()) {
            $this->setInvalid('chess.invalidmove.owncolor');
        } else {
            $this->from->validateMove($this, $this->game->board);
    
            // simulation
            $this->game->board->move($this);
            if ($this->game->board->inCheck($this->game->whitesTurn())) {
                    // ->getKing($this->game->whitesTurn())
                    // ->inCheck($this->game->board)) {
                $this->setInvalid('chess.invalidmove.check');
            }
            $this->game->board->revert();
        }
    }
    
    /**
     * Returns file difference caused by this move.
     * May be negative.
     * @return integer
     */
    public function getFileOffset() {
        return $this->to->file() - $this->from->file();
    }
    
    /**
     * Returns rank difference caused by this move.
     * May be negative.
     * @return integer
     */
    public function getRankOffset() {
        return $this->to->rank() - $this->from->rank();
    }
    
    
    /**
     * Returns a range defined by this moves $from and $to Squares.
     * @see Board::getRange()
     * 
     * @return Range
     */
    public function getPath() {
        return new Range($this->game->board, $this->from, $this->to);
    }
    
    /**
     * Move allowed?
     * @return  boolean
     */
    public function isValid() {
        return $this->valid;
    }
    
    /**
     * When a move turns out to be invalid, use this function to flag it and
     * give a reason why.
     * @param  string  $reason  why is this move invalid? use language variables
     */
    public function setInvalid($reason = '') {
        $this->valid = false;
        $this->invalidReason = $reason;
    }
    
    /**
     * Returns reason why was this move flagged as invalid.
     * @return  string  language variable
     */
    public function getInvalidReason() {
        return $this->invalidReason;
    }
    
    /**
     * Returns a user presentable version of this move if it is valid
     * or a user presentable version of it's invalid reason.
     * @return  string
     */
    public function formatString() {
        if ($this->isValid()) {
            $string = Util::lang(
                'chess.moved',
                $moveData = array(
                    'user'  => Core::getUser()->getName(),
                    'piece' => $this->from->utf8(),
                    'from'  => $this->from->coordinates(),
                    'to'    => $this->to->coordinates()
                )
            );
            if ($this->capture) {
                $string .= Util::lang(
                    'chess.andcaptured',
                    array('capture' => $this->capture->utf8())
                );
            }
            if($this->promotion) {
                $string .= Util::lang(
                    'chess.andpromoted',
                    array('promotion' => $this->promotion->utf8())
                );
            }
            if ($this->game->isCheckmate()) {
                $string .= Util::lang('game.status.checkmate');
            } elseif ($this->game->isCheck()) {
                $string .= Util::lang('game.status.check');
            }
            return $string;
        } else {
            return Util::lang($this->invalidReason);
        }
    }
    
    /**
     * Returns a json encoded (string) representation of this Move's relevant
     * information for use in ajax response
     * @return array
     */
    public function ajaxData() {
        $ajaxData = array(
            'id'      => $this->moveId,
            'from'    => $this->from->coordinates(),
            'to'      => $this->to->coordinates(),
            'valid'   => $this->isValid()
        );
        if ($this->capture) {
            $ajaxData['capture'] = $this->capture->coordinates();
        }
        if ($this->promotion) {
            $ajaxData['promotion'] = $this->promotion->ajaxData();
        }
        if ($this->castling) {
            $ajaxData['castling'] = array(
                'from' => $this->castling['from']->coordinates(),
                'to'   => $this->castling['to']->coordinates()
            );
        }
        // if (!$this->isValid()) {
        //     $ajaxData['invalidReason'] = Core::getLanguage()->getLanguageItem($this->invalidReason);
        // }
        return $ajaxData;
    }
    
    /**
     * Saves this move to database.
     * Also updates this moves moveId.
     */
    public function save() {
        $fields = 'gameId, playerId, chessPiece, fromSquare, toSquare';
        $values = $this->game->getId()
                . ", " . Core::getUser()->getId()
                . ", '" . $this->from->letter() . "'"
                . ", '" . $this->from->coordinates() . "'"
                . ", '" . $this->to->coordinates() . "'";
        if ($this->capture) {
            $fields .= ', capture';
            $values .= ", '" . $this->capture->letter() . $this->capture->coordinates() . "'";
        }
        if ($this->promotion) {
            $fields .= ', promotion';
            $values .= ", '" . $this->promotion->letter() . "'";
        }
        Core::getDB()->sendQuery("INSERT INTO cc_move ({$fields}) VALUES ({$values})");
        $this->moveId = Core::getDB()->getLastInsertId();
    }
    
    /**
     * Checks if given string may be a move
     * pattern supported by this system.
     * DOES NOT validate or execute the move.
     * @param     string   $str
     * @return     boolean
     */
    public static function patternMatch($str) {
        return preg_match('@^'
                . Square::PATTERN
                . self::SEPERATOR_PATTERN . '?'
                . Square::PATTERN
                . ChessPiece::PATTERN . '?'
                . '$@'
            , $str);
        // TODO OPTIONAL add support for algebraic notation
    }
}
