			<section id="gameList">
				<header>
					<h1><?php echo $this->lang('game.list'); ?></h1>
				</header>
				<h2><?php echo $this->lang('game.list.running'); ?></h2>
				<ul class="gameList running"><?php
$runningGames = true;
foreach ($this->var['games'] as $game) {
if ($runningGames && $game->getStatus() >= Game::STATUS_RESIGNED) {
	$runningGames = false;
	echo '</ul><h2>'
		. $this->lang('game.list.finished')
		. '</h2><ul class="gameList finished">';
}
					?><li class="status-<?php echo $game->getStatus(); ?>">
						<a href="<?php echo $this->url('Game/' . $game->getHash()) ?>">
							<dl>
								<dt class="whitePlayer"><?php
									echo $this->lang('game.whiteplayer');
								?></dt>
								<dd class="whitePlayer"><?php
									echo $game->getWhitePlayerName();
								?></dd>
								<dt class="blackPlayer"><?php
									echo $this->lang('game.blackplayer');
								?></dt>
								<dd class="blackPlayer"><?php
									echo $game->getBlackPlayerName();
								?></dd>
								<dt class="status"><?php
									echo $this->lang('game.status');
								?></dt>
								<dd class="status"><?php
									echo $game->getStatusString();
								?></dd>
								<dt class="lastUpdate"><?php
									echo $this->lang('game.lastupdate');
								?></dt>
								<dd class="lastUpdate"><?php
									echo Util::formatTime($game->getLastUpdate());
								?></dd>
							</dl>
						</a>
					</li><?php

}

				?></ul>
			</section><!-- #gameList -->
