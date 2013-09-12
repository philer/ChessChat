<?php
// shortcut
$game = $this->var['game'];

$this->var['chatMsgs'] = $this->controller->getChatController()->getAllMessages($game->getId());

$this->headers();
?>
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
<?php include("_chessboard.tpl.php"); ?>
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
<?php include("_chat.tpl.php"); ?>
			</aside>
