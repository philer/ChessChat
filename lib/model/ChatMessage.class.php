<?php

/**
 * Represents a single message in a chat
 * @author Philipp Miller
 */
class ChatMessage {
	
	/**
	 * userId of this messages author
	 * @var integer
	 */
	public $authorId = 0;
	
	/**
	 * userName of this messages author
	 * May be the name of a bot.
	 * @var string
	 */
	public $authorName = "";
	
	/**
	 * When was this message sent?
	 * @var integer
	 */
	public $timestamp = 0;
	
	/**
	 * message text
	 * @var string
	 */
	public $messageText = "";
	
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
	 * @param 	integer 	$authorId
	 * @param 	string  	$authorName
	 * @param 	string 		$messageText
	 * @param 	integer 	$timestamp
	 * @param 	boolean 	$isBotMsg
	 */
	public function __construct($authorId, $authorName, $messageText, $timestamp = NOW, $isBotMsg = false) {
		$this->authorId = $authorId;
		$this->authorName = $authorName;
		$this->messageText = $messageText;
		$this->timestamp = $timestamp;
		$this->isBotMsg = $isBotMsg;
	}
	
	/**
	 * Determines if this message was sent
	 * by the User who's request is being
	 * processed.
	 * @return 	boolean
	 */
	public function isOwn() {
		return Core::getUser()->getId() == $this->authorId;
	}
	
	/**
	 * Returns the message's send time as a
	 * presentable string
	 * @return 	string
	 */
	public function getFormattedTime() {
		return date('G:i', $this->timestamp);
	}
	
}
