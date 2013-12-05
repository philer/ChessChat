<?php

/**
 * Represents a single message in a chat
 * @author Philipp Miller
 */
class ChatMessage extends DatabaseModel {
    
    /**
     * Unique Id of this chat message
     * @var integer
     */
    public $messageId = 0;
    
    /**
     * gameId of this message's game
     * @var integer
     */
    public $gameId = 0;
    
    /**
     * userId of this message's author
     * @var integer
     */
    public $authorId = 0;
    
    /**
     * userName of this message's author
     * May be the name of a bot.
     * @var string
     */
    public $authorName = '';
    
    /**
     * When was this message sent?
     * @var integer
     */
    public $time = 0;
    
    /**
     * message text
     * @var string
     */
    public $messageText = '';
    
    /**
     * Bot messages are displayed differently
     * @var boolean
     */
    public $isBotMsg = false;
    
    /**
     * A ChatMessage needs to be instanciated with
     * an author and a message text. If no timestamp
     * is supplied it will default to now. If the message
     * is a bot message, the authorName will be treated as
     * the bot's name.
     * @param     integer     $authorId
     * @param     string      $authorName
     * @param     string         $messageText
     * @param     integer     $time
     * @param     boolean     $isBotMsg
     */
    public function __construct(array $msgData = null) {
        if (!is_null($msgData)) {
            if (!isset($msgData['authorId']))
                $this->authorId = Core::getUser()->getId();
            if (!isset($msgData['time']))
                $this->time = NOW;
            parent::__construct($msgData);
        }
    }
    
    /**
     * Determines if this message was sent
     * by the User who's request is being
     * processed.
     * @return     boolean
     */
    public function isOwn() {
        return Core::getUser()->getId() == $this->authorId;
    }
    
    /**
     * Returns the message's send time as a
     * presentable string
     * @return     string
     */
    public function getFormattedTime() {
        return date('G:i', $this->time);
    }
    
}
