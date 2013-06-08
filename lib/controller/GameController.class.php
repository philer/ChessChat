<?php

/**
 * The Game is important.
 * @author Philipp Miller
 */
class GameController implements StandaloneController, AjaxController {
	
	/**
	 * We probably need a Game object.
	 * @var Game
	 */
	protected $game = null;
	
	/**
	 * We probably need a Chat object.
	 * @var Chat
	 */
	protected $chat = null;
	
	/**
	 * Does what needs to be done for this request.
	 */
	public function handleStandaloneRequest() {

		// new Game(); // TODO

		Core::getTemplateEngine()->registerScript("chessboardLayout");
		Core::getTemplateEngine()->registerScript("chat");
		Core::getTemplateEngine()->registerStylesheet("game");
		Core::getTemplateEngine()->registerStylesheet("gameColor");
		Core::getTemplateEngine()->show("game");
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
						$move = escapeString($_POST['move']);
						if (Game::matchMovePattern($move)) {
							$game = new Game();
							$game->move($move);
						}
						break;
					}
				default:
					throw new InvalidAjaxException($_POST['method']." is not a method");
			}
		} else throw new InvalidAjaxException("No method specified");
		
	}
	
	/**
	 * Returns this GameController's Game object
	 * or create a new one if it doesn't exist.
	 * @return 	Game
	 */
	public function getGame() {
		if (isset($this->game)) return $this->game;
		else return new Game();
	}
	
	/**
	 * Returns this GameController's Chat object
	 * or create a new one if it doesn't exist.
	 * @return 	Chat
	 */
	public function getChat() {
		if (isset($this->chat)) return $this->chat;
		else return new Chat();
	}
	
	//TODO
	protected function getUpdate() {}
	
}
