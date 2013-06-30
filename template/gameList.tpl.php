			<section id="gameList">
				<header>
					<h1><?php echo $this->lang('game.list'); ?></h1>
				</header>
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
					?><li class="status-<?php echo $game->getStatus(); ?>">
						<dl class="gameData">
							<dt class="whitePlayer"><?php
								echo $this->lang('game.whiteplayer');
							?></dt>
							<dd class="whitePlayer"><a href="<?php
									echo Util::url($game->getWhitePlayer()->getRoute());
								?>"><?php
									echo $game->getWhitePlayer();
							?></a></dd>
							<dt class="blackPlayer"><?php
								echo $this->lang('game.blackplayer');
							?></dt>
							<dd class="blackPlayer"><a href="<?php
									echo Util::url($game->getBlackPlayer()->getRoute());
								?>"><?php
									echo $game->getBlackPlayer();
							?></a></dd>
							<dt class="status"><?php
								echo $this->lang('game.status');
							?></dt>
							<dd class="status"><?php
								echo $game->getFormattedStatus();
							?></dd>
							<dt class="lastUpdate"><?php
								echo $this->lang('game.lastupdate');
							?></dt>
							<dd class="lastUpdate"><?php
								echo Util::formatTime($game->getLastUpdate());
							?></dd>
						</dl>
						<a href="<?php echo Util::url($game->getRoute()) ?>">
							<span class="icon"><?php
								if ($game->isDraw()) {
									echo new Pawn(false);
								} else {
									echo new King($game->whitesTurn());
								}
							?></span>
							<span><?php
								echo $this->lang('game.list.gotogame');
							?></span>
						</a>
					</li><?php

}

				?></ul>
			</section><!-- #gameList -->
