<?php

/**
 * The Game is important.
 * @author Philipp Miller
 */
class GameController implements RequestController, AjaxController {
	
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
	 * Does what needs to be done for this request.
	 */
	public function handleRequest(array $route) {

		// new Game(); // TODO phil
		// throw new PermissionDeniedException("test");
		
		Core::getTemplateEngine()->registerAsyncScript("chessboardLayout");
		Core::getTemplateEngine()->registerAsyncScript("chess");
		Core::getTemplateEngine()->registerAsyncScript("chat");
		Core::getTemplateEngine()->registerStylesheet("game");
		Core::getTemplateEngine()->showPage("game");
	}
	
	/**
	 * Determines the requested for this ajax request
	 * and executes it with appropriate parameters.
	 */
	public function handleAjaxRequest() {
		
		if (isset($_POST['gameId']) && isset($_POST['method'])) {
			switch ($_POST['method']) {
				
				case "getUpdate":
					$this->getUpdate();
					break;
				
				case "move":
					if (isset($_POST['move'])) {
						$move = $_POST['move'];
						if (Game::matchMovePattern($move)) {
							$this-move($move);
						} else throw new RequestException("'".$move."' is not a valid move");
					} else throw new RequestException("No move specified");
					break;

				case "post":
					if (isset($_POST['msg'])) {
						$msg = htmlspecialchars($_POST['msg']);
						if (Game::matchMovePattern($msg)) {
							$this->move($msg);
						} else {
							$this->chatController = new ChatController($this);
							$this->chatController->post($msg); // TODO
						}
					} else throw new RequestException("No message specified");
					break;
				
				case "offerDraw":
					// TODO
					break;
				
				case "resign":
					// TODO
					break;
				
				default:
					throw new RequestException("Method '".$_POST['method']."' does not exist");
			}
		} else throw new RequestException("No method specified");
		
	}
	
	protected function move($move) {
		$this->game = new Game($this);
		$this->chatController = new ChatController($this);
		
		$success = $this->game->move($move);
		
		if ($success === true) {
			$this->chatController->post(" TODO dynamic langvars; success: ".$move,Core::getUser()->getName());
		} else {
			$this->chatController->post(" TODO dynamic langvars; ".$success." ".$move,Core::getUser()->getName());
		}
	}
	
	//TODO
	protected function getUpdate() {
		// $this->chatController->getUpdate();
	}
	
}
