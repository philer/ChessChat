<?php

/**
 * The Game is important.
 * @author Philipp Miller
 */
class GameController implements RequestController {
	
	/**
	 * We may need a ChatController.
	 * @var Chat
	 */
	protected $chatController = null;
	
	/**
	 * Initializes an optional GameController with a
	 * ChatController as a parent.
	 * @param 	ChatController 	$chatController
	 */
	public function __construct(ChatController $chatController = null) {
		if (!is_null($chatController)) $this->chatController = $chatController;
	}
	
	/**
	 * Returns this GameController's ChatController or creates
	 * a new one if none exists.
	 * @return 	ChatController
	 */
	protected function getChatController() {
		if (is_null($this->chatController)) {
			$this->chatController = new ChatController($this);
		}
		return $this->chatController;
	}
	
	/**
	 * Does what needs to be done for this request.
	 */
	public function handleRequest(array $route) {

		// new Game(); // TODO phil
		
		Core::getTemplateEngine()->registerAsyncScript('game');
		Core::getTemplateEngine()->registerDynamicScript('chess-data');
		Core::getTemplateEngine()->registerStylesheet('game');
		Core::getTemplateEngine()->showPage('game');
	}
	
	/**
	 * TODO
	 */
	public function move($moveString, $gameId) {
		// TODO construct correct game
		$game = new Game($this);
		
		$move = new Move($moveString);
		$game->move($move);
		
		if ($move->valid) {
			AjaxController::queueReply('move', $move->__toString());
			$this->getChatController()->post(
				'TODO dynamic langvars; success: ' . $move,
				$gameId,
				Core::getUser()->getName()
			);
			
		} else {
			AjaxController::queueReply('invalidMove', $move->__toString());
			if (!empty($move->invalidReason)) {
				$this->getChatController()->post(
					$move->invalidReason,
					$gameId,
					Core::getUser()->getName()
				);	
			}
		}
	}
	
	//TODO
	public function getUpdate() {
		// $this->chatController->getUpdate();
	}
	
}
