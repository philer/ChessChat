<?php
// shortcut
$game = $this->var['game'];
?>
			<section id="game">
				<header>
					<h1><a href="<?php
						echo Util::url(
							$game->getWhitePlayer()->getRoute())
					?>"><?php
						echo $game->getWhitePlayer();
					?></a><span class="vs"> vs </span><a href="<?php
						echo Util::url(
							$game->getBlackPlayer()->getRoute())
					?>"><?php
						echo $game->getBlackPlayer();
					?></a></h1>
					<dl class="gameData">
						<dt class="status"><?php
							echo $this->lang('game.status');
						?></dt>
						<dd class="status" id="status"><?php
							echo $game->getFormattedStatus();
						?></dd>
					</dl>
				</header>
<?php include("chessboard.tpl.php"); ?>
<?php if (!is_null($game->isWhitePlayer())) { ?>
				<footer>
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
<?php include("chat.tpl.php"); ?>
			</aside>
