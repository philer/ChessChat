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
        
        if (is_array($moveData)) { // data from database
            
            $this->from = new Square($moveData['fromSquare'], ChessPiece::getInstance($moveData['chessPiece']));
            $this->to   = new Square($moveData['toSquare']);
            
            if (is_null($moveData['capture'])) {
                $this->capture = $this->to;
            } else {
                $this->capture = new Square($moveData['capture']);
                if ($this->to->equals($this->capture)) {
                    // get references right
                    $this->to = $this->capture;
                }
            }
            
            unset($moveData['fromSqare'], $moveData['toSquare'], $moveData['capture']);
            parent::__construct($moveData);
            
        } elseif (self::patternMatch($moveData) && $game !== null) { // new Move
            $moveData = preg_replace('@' . self::SEPERATOR_PATTERN . '@', '', $moveData);
            
            // 'clone' to make sure we can execute this move without changing it
            $this->from = clone $this->game->board->{ $moveData[0] . $moveData[1] };
            $this->to   = clone $this->game->board->{ $moveData[2] . $moveData[3] };
            $this->capture = $this->to; // default case
            
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
     * Returns a user presentable version of this move if it is valid
     * or a user presentable version of it's invalid reason.
     * @return  string
     */
    public function formatString() {
        if ($this->isValid()) {
            return Core::getLanguage()->getLanguageItem(
                'chess.moved',
                array(
                    'user'  => Core::getUser(),
                    'piece' => $this->from->chesspiece->utf8(),
                    'from'  => $this->from,
                    'to'    => $this->to
                )
            );
        } else {
            return Core::getLanguage()->getLanguageItem($this->invalidReason);
        }
    }
    
    /**
     * Validates this move by setting it's $valid and $invalidReason field accordingly.
     * Does basic validation by itself, then initiates piece specific validation.
     */
    public function validate() {
        if (Core::getUser()->getId() != $this->game->getCurrentPlayer()->getId()) {
            $this->setInvalid('chess.invalidmove.notyourturn');
        } elseif ($this->from->isEmpty()) {
            $this->setInvalid('chess.invalidmove.nopiece');
        } elseif (!$this->to->isEmpty() && $this->from->chesspiece->isWhite() == $this->to->chesspiece->isWhite()) {
            $this->setInvalid('chess.invalidmove.owncolor');
        } else {
            $this->from->chesspiece->validateMove($this, $this->game->board);
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
     * @see    Board::range()
     * @return array<Square>
     */
    public function getPath() {
        return $this->game->board->range($this->from, $this->to);
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
                . '$@'
            , $str);
        // TODO OPTIONAL add support for algebraic notation
        // TODO OPTIONAL $piece = '[pkqnbrPKQNBR]'; // language support maybe?
    }
}
