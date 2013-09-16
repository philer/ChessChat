<?php $game = $this->var['game']; ?>
<section id="game">
	<header>
		<h1><?php
			echo new Link(
				$game->getWhitePlayer(),
				$game->getWhitePlayer()->getRoute()
			); ?><span class="vs"> vs </span><?php
			echo new Link(
				$game->getBlackPlayer(),
				$game->getBlackPlayer()->getRoute()
			); ?></h1>
		<dl class="gameData">
			<dt class="status"><?php
				echo $this->lang('game.status');
			?></dt>
			<dd class="status" id="status"><?php
				echo $game->getFormattedStatus();
			?></dd>
		</dl>
	</header>
<?php
$this->show('_chessboard');
if ($game->isPlayer()) {
?>	<footer>
		<nav id="gameMenu">
			<ul>
				<li><a id="resign" class="jsAnchor"><?php
					echo $this->lang('game.menu.resign')
				?></a></li><li><a id="offerDraw" class="jsAnchor"><?php
					echo $this->lang('game.menu.offerdraw')
				?></a></li>
			</ul>
		</nav>
	</footer>
<?php } ?>
</section><!-- #game -->
<aside id="chat">
<?php $this->show('_chat'); ?>
</aside>
