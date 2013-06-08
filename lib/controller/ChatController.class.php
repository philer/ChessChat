<?php

/**
 * Chat mostly responds to ajax requests
 * @author Philipp Miller
 */
class ChatController implements AjaxController {
	
	/**
	 * We probably need a Chat object.
	 * @var Chat
	 */
	protected $chat = null;
	
	/**
	 * We may need a Game object.
	 * @var Game
	 */
	protected $game = null;
	
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
				case "post":
					if (isset($_POST['msg'])) {
						$msg = $_POST['msg'];
						if (Game::matchMovePattern($msg)) {
							$this->game = new Game();
							$this->game->move($msg);
						} else {
							$this->chat = new Chat();
							$this->chat->post($msg);
						}
						break;
					}
				default:
					throw new InvalidAjaxException($_POST['method']." is not a method");
			}
		} else throw new InvalidAjaxException("No method specified");
		
	}
	
	/**
	 * Returns this ChatController's Chat object
	 * or create a new one if it doesn't exist.
	 * @return 	Chat
	 */
	public function getChat() {
		if (isset($this->chat)) return $this->chat;
		else return new Chat();
	}
		
	/**
	 * Returns this ChatController's Game object
	 * or create a new one if it doesn't exist.
	 * @return 	Game
	 */
	public function getGame() {
		if (isset($this->game)) return $this->game;
		else return new Game();
	}
	
	//TODO
	protected function getUpdate() {}
	
}
