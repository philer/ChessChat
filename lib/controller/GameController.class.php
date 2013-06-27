<?php

/**
 * The Game is important.
 * @author Philipp Miller
 */
class GameController implements RequestController {
	
	/**
	 * We may need a Game object.
	 * @var Game
	 */
	protected $game = null;
	
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
		// throw new PermissionDeniedException('test');
		
		Core::getTemplateEngine()->registerAsyncScript('chessboardLayout');
		Core::getTemplateEngine()->registerAsyncScript('chess');
		Core::getTemplateEngine()->registerAsyncScript('chat');
		Core::getTemplateEngine()->registerDynamicScript('chess-data');
		Core::getTemplateEngine()->registerStylesheet('game');
		Core::getTemplateEngine()->showPage('game');
	}
	
	/**
	 * TODO
	 */
	public function move($move, $gameId) {
		// TODO request correct game
		$this->game = new Game($this);
		
		$success = $this->game->move($move);
		
		if ($success === true) {
			AjaxController::queueReply('move', strtoupper($move));
			$this->getChatController()->post(
				'TODO dynamic langvars; success: ' . $move,
				$gameId,
				Core::getUser()->getName()
			);
			
		} else {
			$this->getChatController()->post(
				'TODO dynamic langvars; ' . $success . ' ' . $move,
				$gameId,
				Core::getUser()->getName()
			);
		}
	}
	
	//TODO
	public function getUpdate() {
		// $this->chatController->getUpdate();
	}
	
}
