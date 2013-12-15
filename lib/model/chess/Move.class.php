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
     * Boolean if castling takes place. Null otherwise
     * true indicates Queenside, false indicates Kingside castling.
     * @var boolean/null
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
     * @param  string/array  $moveData
     * @param  Game          $game      game in which this move was made
     */
    public function __construct($moveData, Game $game = null) {
        $this->game = $game;
        
        if (is_array($moveData)) {
            // got data from database (should be valid)
            $this->from = new Square($moveData['fromSquare'], null, ChessPiece::getInstance($moveData['chessPiece']));
            $this->to   = new Square($moveData['toSquare']);
            // capturing
            if (is_null($moveData['capture'])) {
                $this->capture = $this->to;
            } else {
                $this->capture = new Square($moveData['capture']);
                if ($this->to->equals($this->capture)) {
                    // get references right
                    $this->to = $this->capture;
                }
            }
            
            // promotion
            if ($moveData['promotion']) {
                $this->promotion = ChessPiece::getInstance($moveData['promotion']);
            }
            
            // castling
            $foff = $this->getFileOffset();
            if ($this->from->chesspiece instanceof King && abs($foff) == 2 ) {
                $this->castling['from'] = new Square($foff > 0 ? 'h' : 'a', $this->from->rank());
                $this->castling['to']   = new Square($foff > 0 ? 'f' : 'd', $this->from->rank());
            }
            
            // other data
            unset($moveData['fromSqare'], $moveData['toSquare'], $moveData['capture'], $moveData['promotion']);
            parent::__construct($moveData);
            
        } elseif (self::patternMatch($moveData) && $game !== null) {
            // creating new Move (requires validation)
            
            $moveData = preg_replace('@' . self::SEPERATOR_PATTERN . '@', '', $moveData);
            
            $this->from = $this->game->board->{ $moveData[0] . $moveData[1] };
            $this->to   = $this->game->board->{ $moveData[2] . $moveData[3] };
            $this->capture = $this->to; // default case
            
            if (strlen($moveData) == 5) {
                $this->promotion = ChessPiece::getInstance($moveData[4]);
            }
            
            $this->validate();
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
        } elseif (!$this->to->isEmpty() && $this->from->chesspiece->isWhite() == $this->to->chesspiece->isWhite()) {
            $this->setInvalid('chess.invalidmove.owncolor');
        } else {
            $this->from->chesspiece->validateMove($this, $this->game->board);
    
            // simulation
            $this->game->board->move($this);
            if ($this->game->board->inCheck($this->game->whitesTurn())) {
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
                    'user'  => Core::getUser(),
                    'piece' => (string) $this->from->chesspiece,
                    'from'  => (string) $this->from,
                    'to'    => (string) $this->to
                )
            );
            if (!$this->capture->isEmpty()) {
                $string .= Util::lang(
                    'chess.andcaptured',
                    array('capture' => (string) $this->capture->chesspiece)
                );
            }
            if($this->promotion) {
                $string .= Util::lang(
                    'chess.andpromoted',
                    array('promotion' => (string) $this->promotion)
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
            'from'    => (string) $this->from,
            'to'      => (string) $this->to,
            'valid'   => $this->isValid()
        );
        if (!$this->capture->isEmpty()) {
            $ajaxData['capture'] = (string) $this->capture;
        }
        if ($this->promotion) {
            $ajaxData['promotion'] = $this->promotion->ajaxData();
        }
        if (!empty($this->castling)) {
            $ajaxData['castling'] = array(
                'from' => (string) $this->castling['from'],
                'to'   => (string) $this->castling['to']
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
                . ", '" . $this->from->chesspiece->letter() . "'"
                . ", '" . $this->from . "'"
                . ", '" . $this->to . "'";
        if (!$this->capture->isEmpty()) {
            $fields .= ', capture';
            $values .= ", '" . $this->capture->chesspiece->letter() . (string) $this->capture . "'";
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
