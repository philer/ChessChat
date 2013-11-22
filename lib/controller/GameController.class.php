<?php

/**
 * The Game is important.
 * @author Philipp Miller
 */
class GameController extends AbstractRequestController {
	
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
		parent::__construct();
		$this->chatController = $chatController;
	}
	
	/**
	 * Returns this GameController's ChatController or creates
	 * a new one if none exists.
	 * @return 	ChatController
	 */
	public function getChatController() {
		if (is_null($this->chatController)) {
			$this->chatController = new ChatController($this);
		}
		return $this->chatController;
	}
	
	/**
	 * Does what needs to be done for this request.
	 * @param array $route
	 */
	public function handleRequest(array $route) {
		if (is_null($param = array_shift($route))) {
			// show games list
			$this->prepareGameList();
			Core::getTemplateEngine()->showPage('gameList', $this);
			return;
		}
		if (Game::hashPatternMatch($param)) {
			// show specified game
			$this->prepareGame($param);
			Core::getTemplateEngine()->showPage('game', $this);
			return;
		}
		
		// method
		$this->route .= $param;
		switch ($param) {
			case 'new':
				if (!$this->create()) {
					Core::getTemplateEngine()->showPage('gameForm', $this);
				}
				break;
			
			case 'settings':
				throw new NotFoundException('not implemented');
				break;
			
			default:
				throw new NotFoundException('method doesn\'t exist');
				break;
		}
	}
	
	/**
	 * Creates a new Game from provided form data.
	 * If creation failes due to incorrect user input
	 * assigns template variables for form values.
	 * @return boolean creation success
	 */
	public function create() {
		if (Core::getUser()->isGuest()) {
			// TODO manage this somewhere else
			throw new PermissionDeniedException('need to be logged in');
		}
		if (isset($_POST['opponent']) && isset($_POST['whitePlayer'])) {
			
			$opponentData = Core::getDB()->sendQuery(
					"SELECT userId
					 FROM cc_user
					 WHERE userName = '" . Util::esc(trim($_POST['opponent'])) . "'"
				)->fetch_assoc();
		 	
			if (!empty($opponentData)) {
				$gameData = array();
				if ($_POST['whitePlayer'] === 'self') {
					$gameData['whitePlayerId'] = Core::getUser()->getId();
					$gameData['blackPlayerId'] = $opponentData['userId'];
				} else {
					$gameData['whitePlayerId'] = $opponentData['userId'];
					$gameData['blackPlayerId'] = Core::getUser()->getId();
				}
				$game = new Game($gameData);
				$game->save();
				header('Location: ' . Util::url('Game/' . $game->getHash()));
				return true;
			}
		 	Core::getTemplateEngine()->addVar('errorMessage', 'form.invalid');
		 	Core::getTemplateEngine()->addVar('invalid', array('opponent'));
		}
		$this->pageTitle = Core::getLanguage()->getLanguageItem('game.new');
		return false;
	}
	
	/**
	 * Validates and Executes a chess move
	 * @param string $moveString user input (chess notation)
	 * @param int    $gameId
	 */
	public function move($moveString, $gameId) {
		$gameData = Core::getDB()->sendQuery(
			'SELECT gameId,
			        W.userId   as whitePlayerId,
			        W.userName as whitePlayerName,
			        B.userId   as blackPlayerId,
			        B.userName as blackPlayerName,
			        board as boardString,
			        status
			 FROM cc_game
				JOIN cc_user W ON cc_game.whitePlayerId = W.userId
				JOIN cc_user B ON cc_game.blackPlayerId = B.userId
			 WHERE gameId = ' . intval($gameId)
		)->fetch_assoc();
		if (empty($gameData)) throw new NotFoundException('game doesn\'t exist');
		
		$game = new Game($gameData);
		$move = new Move($moveString, $game);
		AjaxUtil::queueReply('move', $move->ajaxData());
		
		if ($move->isValid()) {
			$game->move($move);
			$game->update();
			$move->save();
			AjaxUtil::queueReply('status', $game->getFormattedStatus());
			$this->getChatController()->post(
				Core::getLanguage()->getLanguageItem(
					'chess.moved',
					array(
						'user'  => Core::getUser(),
						'piece' => $move->getChesspiece()->utf8(),
						'from'  => $move->from(),
						'to'    => $move->to()
					)
				),
				$gameId,
				Core::getUser()->getName()
			);
		} else {
			// also sent via ajax move object
			$this->getChatController()->post(
				$move->getInvalidReason(),
				$gameId,
				Core::getUser()->getName(),
				false
			);
		}
	}
	
	//TODO
	public function getUpdate() {
		// $this->chatController->getUpdate();
	}
	
	/**
	 * Prepares data for a game list to be used
	 * in templates. If a $userId > 0 is specified
	 * only games that include this player will be displayed.
	 * @param  integer $userId
	 */
	public function prepareGameList($userId = 0) {
		$sql = 'SELECT gameHash,
			           W.userId   as whitePlayerId,
			           W.userName as whitePlayerName,
			           B.userId   as blackPlayerId,
			           B.userName as blackPlayerName,
			           status,
			           UNIX_TIMESTAMP(lastUpdate) as lastUpdate
			    FROM cc_game G
			         JOIN cc_user W ON G.whitePlayerId = W.userId
			         JOIN cc_user B ON G.blackPlayerId = B.userId';
		if ($userId > 0) {
			$sql .= ' AND (G.whitePlayerId = ' . intval($userId)
			      . '  OR  G.blackPlayerId = ' . intval($userId) . ') ';
		}
		$sql .= ' ORDER BY status, lastUpdate
			      LIMIT 30';
		$gamesData = Core::getDB()->sendQuery($sql);
		$games = array();
		while ($gameData = $gamesData->fetch_assoc()) {
			$games[] = new Game($gameData);
		}
		
		Core::getTemplateEngine()->addVar('games', $games);
		Core::getTemplateEngine()->registerStylesheet('game');
		$this->pageTitle = Core::getLanguage()->getLanguageItem('game.list');
	}
	
	/**
	 * Prepares data for a game to be used in templates.
	 * @param  string $gameHash
	 */
	public function prepareGame($gameHash) {
		$gameData = Core::getDB()->sendQuery(
		 	"SELECT gameId,
			        gameHash,
			        W.userId   as whitePlayerId,
			        W.userName as whitePlayerName,
			        B.userId   as blackPlayerId,
			        B.userName as blackPlayerName,
			        board as boardString,
			        status,
			        UNIX_TIMESTAMP(lastUpdate) as lastUpdate
			 FROM cc_game
				JOIN cc_user W ON cc_game.whitePlayerId = W.userId
				JOIN cc_user B ON cc_game.blackPlayerId = B.userId
			 WHERE gameHash = '" . Util::esc($gameHash) . "'"
	 	)->fetch_assoc();
		if (empty($gameData)) throw new NotFoundException('game doesn\'t exist');
		
		$game = new Game($gameData);
		
		Core::getTemplateEngine()->addVar('game', $game);
		Core::getTemplateEngine()->addVar('chatMsgs', $this->getChatController()->getAllMessages($game->getId()));
		
		Core::getTemplateEngine()->registerDynamicScript('game-data');
		Core::getTemplateEngine()->registerAsyncScript('game');
		Core::getTemplateEngine()->registerStylesheet('game');
		$this->pageTitle = $game->getWhitePlayer()
		                 . ' vs '
		                 . $game->getBlackPlayer();
		$this->route     = $game->getRoute();
	}
}
