<?php

/**
 * Chat mostly responds to ajax requests
 * @author Philipp Miller
 */
class ChatController extends AjaxController {
	
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
		
		if (isset($_POST['msg']) && isset($_POST['gameId'])) {
			
			switch ($_POST['msg']) {
				
				case '/getUpdate':
				case '/update':
					// TODO
				
				case '/offerDraw':
				case '/draw':
					// TODO
						
				case '/resign':
					// TODO
					$this->post(
						'<em>' . htmlspecialchars($_POST['msg']) . ' is not implemented yet</em>',
						intval($_POST['gameId']),
						Core::getUser()->getName()
					);
					break;
					
				default:
					if (Move::patternMatch($_POST['msg'])) {
						$this->getGameController()->move(
							$_POST['msg'],
							intval($_POST['gameId'])
						);
					} else {
						$this->post(
							htmlspecialchars($_POST['msg']),
							intval($_POST['gameId'])
						);
					}
					break;
			}
		} else throw new RequestException('Bad Ajax Request');
		
	}
	
	/**
	 * A user has sent a new chat message which will be stored
	 * and set up for broadcast.
	 * @param 	string 	$msg
	 * @param 	integer $gameId
	 * @param 	string 	$botName
	 */
	public function post($msg, $gameId, $botName = '') {
		if (empty($botName)) {
			$msgObj = new ChatMessage(
				$gameId,
				Core::getUser()->getId(),
				Core::getUser()->getName(),
				$msg
			);
			
		} else {
			$msgObj = new ChatMessage($gameId, 0, $botName, $msg, NOW, true);
		}
		// TODO mysql_escape, save
		// queue reply
		Core::getTemplateEngine()->addVar('msg',$msgObj);
		self::queueReply('msg', Core::getTemplateEngine()->fetch('_chatMessage'));
	}
	
	//TODO
	public function getNewMessages() {}
	public function getAllMessages() {}
	public function getUpdate() {}
	
}
