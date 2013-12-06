                <h2><?php echo $this->lang('game.list.running'); ?></h2>
                <ul class="gameList dataList"><?php
$runningGames = true;
foreach ($this->var['games'] as $game) {
if ($runningGames && $game->getStatus() >= Game::STATUS_RESIGNED) {
    $runningGames = false;
    echo '</ul><h2>'
        . $this->lang('game.list.finished')
        . '</h2><ul class="gameList dataList">';
}
                    ?><li class="status-<?php
                        echo $game->getStatus();
                        if ($game->ownTurn()) echo ' ownturn';
                    ?>">
                        <dl class="gameData">
                            <dt class="whitePlayer heading"><?php
                                echo $this->lang('game.whiteplayer');
                            ?></dt>
                            <dd class="whitePlayer heading"><?php
                                    echo new Link(
                                        $game->getWhitePlayer(),
                                        $game->getWhitePlayer()->getRoute()
                                    ); ?></dd>
                            <dt class="blackPlayer heading"><?php
                                echo $this->lang('game.blackplayer');
                            ?></dt>
                            <dd class="blackPlayer heading"><?php
                                    echo new Link(
                                        $game->getBlackPlayer(),
                                        $game->getBlackPlayer()->getRoute()
                                    ); ?></dd>
                            <dt class="lastUpdate"><?php
                                echo $this->lang('game.lastupdate');
                            ?></dt>
                            <dd class="lastUpdate"><?php
                                echo Util::formatTime($game->getLastUpdate());
                            ?></dd>
                            <dt class="status"><?php
                                echo $this->lang('game.status');
                            ?></dt>
                            <dd class="status"><?php
                                echo $game->getFormattedStatus();
                            ?></dd>
                        </dl>
                        <a href="<?php echo Util::url($game->getRoute()) ?>">
                            <span class="icon statusicon chesspiece"><?php echo $game->getStatusIcon() ?></span>
                            <span><?php
                                echo $this->lang('game.list.gotogame');
                            ?></span>
                        </a>
                    </li><?php
}
                ?></ul>
