<?php

/**
 * The Game is important.
 * @author Philipp Miller
 */
class GameController extends AbstractRequestController implements AjaxController {
    
    /**
     * We may need a ChatController.
     * @var Chat
     */
    protected $chatController = null;
    
    /**
     * Initializes an optional GameController with a
     * ChatController as a parent.
     * @param     ChatController     $chatController
     */
    public function __construct(ChatController $chatController = null) {
        parent::__construct();
        $this->chatController = $chatController;
    }
    
    /**
     * Returns this GameController's ChatController or creates
     * a new one if none exists.
     * @return     ChatController
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
     * Executes ajax actions
     */
    public function handleAjaxRequest() {
        if (isset($_POST['action']) && isset($_POST['gameId'])) {
            switch ($_POST['action']) {
                
                case 'getUpdate' : 
                    $this->getUpdate($_POST['gameId']);
                    break;
                
                case 'move' : 
                    $this->move(
                        $_POST['move'],
                        $_POST['gameId']
                    );
                    break;
                
                default :
                    throw new RequestException('Action "' . $_POST['action'] . '" unknown');
            }
        } else throw new RequestException('Bad Ajax Request');
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
                header('Location: ' . Util::url($game->getRoute()));
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
             FROM cc_game G
                JOIN cc_user W ON G.whitePlayerId = W.userId
                JOIN cc_user B ON G.blackPlayerId = B.userId
             WHERE gameId = ' . intval($gameId)
        )->fetch_assoc();
        if (empty($gameData)) throw new NotFoundException('game doesn\'t exist');
        
        $game = new Game($gameData);
        $move = new Move($moveString, $game);
        
        if ($move->isValid()) {
            
            $game->move($move);
            
            $move->save();
            $game->update();
            
            AjaxUtil::queueReply('status', $game->getFormattedStatus());
        }
        AjaxUtil::queueReply('move', $move->ajaxData());
        $this->getChatController()->post(
            $move->formatString(),
            $gameId,
            Core::getUser()->getName(),
            $move->isValid() // don't save invalid messages
        );
    }
    
    /**
     * Prepares changes to an active game since the last update
     * @param  int $gameId
     */
    public function getUpdate($gameId) {
        $moves = $this->getNewMoves($gameId, $_POST['lastMoveId']);
        // don't send our own moves (request may occur on slow connections)
        if (!empty($moves) && $moves[0]->playerId != Core::getUser()->getId()) {
            $gameData = Core::getDB()->sendQuery(
                 "SELECT status,
                         W.userId   as whitePlayerId,
                         W.userName as whitePlayerName,
                         B.userId   as blackPlayerId,
                         B.userName as blackPlayerName
                 FROM cc_game G
                    JOIN cc_user W ON G.whitePlayerId = W.userId
                    JOIN cc_user B ON G.blackPlayerId = B.userId
                 WHERE gameId = " . intval($gameId)
             )->fetch_assoc();
            $game = new Game($gameData);
            
            AjaxUtil::queueReply('status', $game->getFormattedStatus());
            AjaxUtil::queueReply(
                'moves',
                array_map(
                    function($move) { return $move->ajaxData(); },
                    $moves
                    )
            );
        }
    }
    
    /**
     * Returns an array containing all moves since $lastId
     * @param  int $gameId
     * @param  int $lastId
     * @return array<Move>
     */
    public function getNewMoves($gameId, $lastId) {
        $movesData = Core::getDB()->sendQuery(
            'SELECT moveId,
                    playerId,
                    chessPiece,
                    fromSquare,
                    toSquare,
                    capture,
                    promotion
             FROM cc_move
             WHERE  moveId > ' . intval($lastId) . '
                AND gameId = '    . intval($gameId) . '
             ORDER BY moveId ASC'
        );
        $moves = array();
        while ($moveData = $movesData->fetch_assoc()) {
            $moves[] = new Move($moveData);
        }
        return $moves;
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
        $sql .= ' ORDER BY lastUpdate DESC, status
                  LIMIT 100';
        $gamesData = Core::getDB()->sendQuery($sql);
        $games = array(
            'running' => array(),
            'over'    => array()
            );
        while ($gameData = $gamesData->fetch_assoc()) {
            $game = new Game($gameData);
            if ($game->isOver()) {
                $games['over'][] = $game;
            } else {
                $games['running'][] = $game;
            }
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
                     coalesce(
                         (SELECT max(M.moveId)
                             FROM cc_move M
                             WHERE M.gameId = G.gameId
                         ),
                         0
                     ) as lastMoveId,
                     UNIX_TIMESTAMP(lastUpdate) as lastUpdate
             FROM cc_game G
                JOIN cc_user W ON G.whitePlayerId = W.userId
                JOIN cc_user B ON G.blackPlayerId = B.userId
             WHERE gameHash = '" . Util::esc($gameHash) . "'"
         )->fetch_assoc();
        if (empty($gameData)) throw new NotFoundException('game doesn\'t exist');
        
        $game = new Game($gameData);
        
        Core::getTemplateEngine()->addVar('game', $game);
        Core::getTemplateEngine()->addVar('chatMsgs', $this->getChatController()->getAllMessages($game->getId()));
        
        Core::getTemplateEngine()->registerDynamicScript('game-data');
        Core::getTemplateEngine()->registerScript('chess');
        Core::getTemplateEngine()->registerScript('chat');
        Core::getTemplateEngine()->registerStylesheet('game');
        $this->pageTitle = $game->getWhitePlayer()
                         . ' vs '
                         . $game->getBlackPlayer();
        $this->route     = $game->getRoute();
    }
}
