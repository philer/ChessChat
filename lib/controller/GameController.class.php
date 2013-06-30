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
		
		if (!empty($route)) {
			switch ($x = array_shift($route)) {
				case 'new':
					// TODO
					throw new NotFoundException('not implemented');
					break;
				
				default:
					// new Game(); // TODO phil
					Core::getTemplateEngine()->registerAsyncScript('game');
					Core::getTemplateEngine()->registerDynamicScript('chess-data');
					Core::getTemplateEngine()->registerStylesheet('game');
					Core::getTemplateEngine()->showPage('game');
					break;
			}
		} else {
			$gamesData = Core::getDB()->sendQuery(
				'SELECT `gameId`,
				        `gameHash`,
				        `W`.`userId`   as `whitePlayerId`,
				        `W`.`userName` as `whitePlayerName`,
				        `B`.`userId`   as `blackPlayerId`,
				        `B`.`userName` as `blackPlayerName`,
				        `status`,
				        UNIX_TIMESTAMP(`lastUpdate`) as `lastUpdate`
				 FROM `cc_game`
					JOIN `cc_user` `W` ON `cc_game`.`whitePlayerId` = `W`.`userId`
					JOIN `cc_user` `B` ON `cc_game`.`blackPlayerId` = `B`.`userId`
				 ORDER BY `status`, `lastUpdate`'
			);
			
			$games = array();
			while ($gameData = $gamesData->fetch_assoc()) {
				$games[] = new Game($gameData);
			}
			
			Core::getTemplateEngine()->addVar('games', $games);
			Core::getTemplateEngine()->registerStylesheet('gameList');
			Core::getTemplateEngine()->showPage('gameList');
		}
	}
	
	/**
	 * TODO
	 */
	public function move($moveString, $gameId) {
		// TODO construct correct game
		$game = new Game();
		
		$move = new Move($moveString);
		$game->move($move);
		
		if ($move->valid) {
			AjaxController::queueReply('move', $move->__toString());
			$this->getChatController()->post(
				Core::getLanguage()->getLanguageItem(
					'chess.moved',
					// TODO Move info
					array(
						'user'  => Core::getUser(),
						'piece' => '[piece]',
						'from'  => '[from]',
						'to'    => '[to]')
				),
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
