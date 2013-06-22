<?php

/**
 * Chat mostly responds to ajax requests
 * @author Philipp Miller
 */
class ChatController {
	
	/**
	 * Every ChatController belongs to a GameController
	 * @var GameController
	 */
	protected $gameController = null;
	
	/**
	 * Initializes a ChatController with a
	 * GameController as a parent.
	 * @param 	GameController 	$gameController
	 */
	public function __construct($gameController) {
		$this->gameController = $gameController;
	}
	
	/**
	 * A user has sent a new chat message which will be stored
	 * and set up for broadcast.
	 * @param 	string 	$msg
	 * @param 	string 	$botName
	 */
	public function post($msg, $botName = "") {
		if (empty($botName)) {
			$msgObj = new ChatMessage(Core::getUser()->getId(), Core::getUser()->getName(), $msg);
		} else {
			$msgObj = new ChatMessage(0, $botName, $msg, NOW, true);
		}
		// TODO save
		Core::getTemplateEngine()->addVar('msg',$msgObj);
		// TODO json_encode
		Core::getTemplateEngine()->show("chatMessage");
	}
	
	//TODO
	public function getNewMessages() {}
	public function getAllMessages() {}
	public function getUpdate() {}
	
}
