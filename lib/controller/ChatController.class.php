<?php

/**
 * Chat mostly responds to ajax requests
 * @author Philipp Miller
 */
class ChatController implements AjaxController {
	
	/**
	 * Every ChatController belongs to a GameController
	 * @var GameController
	 */
	protected $gameController = null;
	
	/**
	 * Initializes a ChatController with an optional
	 * GameController as a parent.
	 * @param 	GameController 	$gameController
	 */
	public function __construct(GameController $gameController = null) {
		if (!is_null($gameController)) $this->gameController = $gameController;
	}
	
	/**
	 * Returns this ChatController's GameController or creates
	 * a new one if none exists.
	 * @return 	GameController
	 */
	protected function getGameController() {
		if (is_null($this->gameController)) {
			$this->gameController = new GameController($this);
		}
		return $this->gameController;
	}
	
	/**
	 * Determines the requested for this ajax request
	 * and executes it with appropriate parameters.
	 */
	public function handleAjaxRequest() {
		if (isset($_POST['action']) && isset($_POST['gameId'])) {
			switch ($_POST['action']) {
				case 'getUpdate' : 
					$this->getUpdate($_POST['gameId']);
					break;
				
				// case '/offerDraw':
				// case '/draw':
				// 	// TODO
						
				// case '/resign':
				// 	// TODO
				// 	$this->post(
				// 		'<em>' . htmlspecialchars($_POST['msg']) . ' is not implemented yet</em>',
				// 		intval($_POST['gameId']),
				// 		Core::getUser()->getName()
				// 	);
				// 	break;
				
				case 'msg' :
					if (Move::patternMatch($_POST['msg'])) {
						$this->getGameController()->move(
							$_POST['msg'],
							$_POST['gameId']
						);
					} else {
						$this->post(
							htmlspecialchars($_POST['msg']),
							$_POST['gameId']
						);
					}
					break;
				
				case 'move' : 
					if (Move::patternMatch($_POST['move'])) {
						$this->getGameController()->move(
							$_POST['move'],
							$_POST['gameId']
						);
					} else throw new RequestException('Not a valid move');
					break;
					
				default :
					throw new RequestException('Action ' . $_POST['action'] . ' unknown');
					break;
			}
		
		} else throw new RequestException('Bad Ajax Request');
		
	}
	
	/**
	 * A user has sent a new chat message which will be stored
	 * and set up for broadcast.
	 * @param string 	$msg
	 * @param integer $gameId
	 * @param string 	$botName
	 * @param boolean $save store this message in database?
	 */
	public function post($msg, $gameId, $botName = '', $save = true) {
		$msgObj = new ChatMessage(array(
			'gameId'      => $gameId,
			'messageText' => $msg,
		));
		
		if ($botName != '') {
			$msgObj->authorName = $botName;
			$msgObj->isBotMsg   = true;
		} else {
			$msgObj->authorName = Core::getUser()->getName();
		}
		
		if ($save) {
			Core::getDB()->sendQuery("
				INSERT INTO cc_chatMessage (gameId, authorId, messageText, time, isBotMsg)
				VALUES ("  . intval($gameId) . ",
				        "  . $msgObj->authorId . ",
				        '" . Util::esc($msg) . "',
				        '" . $msgObj->time . "',
				        "  . (int) $msgObj->isBotMsg . ")
				");	
		}
		
		// queue reply
		Core::getTemplateEngine()->addVar('msg', $msgObj);
		AjaxUtil::queueReply('msg', Core::getTemplateEngine()->fetch('_chatMessage'));
	}
	
	/**
	 * Sends new messages since last update request.
	 * @param  integer $gameId
	 */
	public function getUpdate($gameId) {
		$msgs = $this->getNewMessages($gameId, $_POST['lastId']);
		if (!empty($msgs)) {
			$reply = '';
			foreach ($msgs as $msg) {
				Core::getTemplateEngine()->addVar('msg', $msg);
				$reply .= Core::getTemplateEngine()->fetch('_chatMessage');
			}
			AjaxUtil::queueReply('msg', $reply);
			AjaxUtil::queueReply('lastId', $msg->messageId);
		}
	}
	
	/**
	 * Returns new messages since last update request, without own messages.
	 * @param  integer $gameId
	 * @param  integer $lastId Id of the last message that was sent
	 * @return array<ChatMessage>
	 */
	public function getNewMessages($gameId, $lastId) {
		$messages = Core::getDB()->sendQuery(
			'SELECT messageId,
			        gameId,
			        authorId,
			        userName as authorName,
			        messageText,
			        time,
			        isBotMsg
			 FROM   cc_chatMessage, cc_user
			 WHERE  messageId > ' . intval($lastId) . '
			    AND gameId = '    . intval($gameId) . '
			    AND authorId != ' . Core::getUser()->getId() . '
			    AND userId = authorId
			 ORDER BY messageId ASC'
		);
		$msgObjects = array();
		while ($msg = $messages->fetch_object('ChatMessage')) {
			$msgObjects[] = $msg;
		}
		return $msgObjects;
	}
	
	/**
	 * Returns all messages written in this game so far.
	 * @param  integer $gameId
	 * @return array<ChatMessage>
	 */
	public function getAllMessages($gameId) {
		$messages = Core::getDB()->sendQuery(
			'SELECT messageId,
			        gameId,
			        authorId,
			        userName as authorName,
			        messageText,
			        time,
			        isBotMsg
			 FROM   cc_chatMessage, cc_user
			 WHERE  gameId = '    . intval($gameId) . '
			    AND userId = authorId
			 ORDER BY messageId ASC'
		);
		$msgObjects = array();
		while ($msg = $messages->fetch_object('ChatMessage')) {
			$msgObjects[] = $msg;
		}
		return $msgObjects;
	}
	
}
