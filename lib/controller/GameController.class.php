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
		if (is_null($param = array_shift($route))) {
			// show games list
			$gamesData = Core::getDB()->sendQuery(
				'SELECT `gameHash`,
				        `W`.`userId`   as `whitePlayerId`,
				        `W`.`userName` as `whitePlayerName`,
				        `B`.`userId`   as `blackPlayerId`,
				        `B`.`userName` as `blackPlayerName`,
				        `status`,
				        UNIX_TIMESTAMP(`lastUpdate`) as `lastUpdate`
				 FROM `cc_game`
					JOIN `cc_user` `W` ON `cc_game`.`whitePlayerId` = `W`.`userId`
					JOIN `cc_user` `B` ON `cc_game`.`blackPlayerId` = `B`.`userId`
				 ORDER BY `status`, `lastUpdate`
				 LIMIT 30'
			);
			
			$games = array();
			while ($gameData = $gamesData->fetch_assoc()) {
				$games[] = new Game($gameData);
			}
			
			Core::getTemplateEngine()->addVar('games', $games);
			Core::getTemplateEngine()->registerStylesheet('game');
			Core::getTemplateEngine()->showPage('gameList');
			return;
		}
		
		// show specified game
		if (Game::hashPatternMatch($param)) {
			
			$gameData = Core::getDB()->sendQuery(
			 	"SELECT `gameId`,
			 			`W`.`userId`   as `whitePlayerId`,
				        `W`.`userName` as `whitePlayerName`,
				        `B`.`userId`   as `blackPlayerId`,
				        `B`.`userName` as `blackPlayerName`,
				        `board` as `boardString`,
				        `status`,
				        UNIX_TIMESTAMP(`lastUpdate`) as `lastUpdate`
				 FROM `cc_game`
					JOIN `cc_user` `W` ON `cc_game`.`whitePlayerId` = `W`.`userId`
					JOIN `cc_user` `B` ON `cc_game`.`blackPlayerId` = `B`.`userId`
				 WHERE `gameHash` = '" . Util::esc($param) . "'"
		 	)->fetch_assoc();
			
			if (!empty($gameData)) {
				$game = new Game($gameData);
				if (!is_null($game->isWhitePlayer())) {
					// provide script for user interaction
					Core::getTemplateEngine()->registerDynamicScript('game-data');
				}
				Core::getTemplateEngine()->addVar('game', $game);
				Core::getTemplateEngine()->registerAsyncScript('game');
				Core::getTemplateEngine()->registerStylesheet('game');
				Core::getTemplateEngine()->showPage('game');
				return;
			}
			
			throw new NotFoundException('game doesn\'t exist');

		}
		
		// method
		switch ($x = array_shift($route)) {
			case 'new':
				// TODO
				throw new NotFoundException('not implemented');
				break;
			
			default:
				throw new NotFoundException('method doesn\'t exist');
				break;
		}
	}
	
	/**
	 * TODO
	 */
	public function move($moveString, $gameId) {
		// TODO construct correct game
		$gameData = Core::getDB()->sendQuery(
			'SELECT `gameId`,
			        `W`.`userId`   as `whitePlayerId`,
			        `W`.`userName` as `whitePlayerName`,
			        `B`.`userId`   as `blackPlayerId`,
			        `B`.`userName` as `blackPlayerName`,
			        `board` as `boardString`
			 FROM `cc_game`
				JOIN `cc_user` `W` ON `cc_game`.`whitePlayerId` = `W`.`userId`
				JOIN `cc_user` `B` ON `cc_game`.`blackPlayerId` = `B`.`userId`
			 WHERE `gameId` = ' . $gameId
		)->fetch_assoc();
		
		if (!empty($gameData)) $game = new Game($gameData);
		else throw new NotFoundException('game not found');
		
		$move = new Move($moveString);
		$game->move($move);
		
		if ($move->valid) {
			AjaxController::queueReply('move', $move->__toString());
			AjaxController::queueReply('status', $game->getFormattedStatus());
			$this->getChatController()->post(
				Core::getLanguage()->getLanguageItem(
					'chess.moved',
					// TODO Move info
					array(
						'user'  => Core::getUser(),
						'piece' => '[piece]',
						'from'  => '[from]',
						'to'    => '[to]')
				) . ' (' . $move . ')', // TEST
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
