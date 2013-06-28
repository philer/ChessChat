			<section id="game">
				<header>
					<h1>Phil <span class="vs">vs</span> Larissa</h1>
					<div id="clock">
						<span id="timer">3:00</span>
						<span id="playtime">0:27:49</span>
					</div><!--#clock-->
				</header>
<?php include("chessboard.tpl.php"); ?>
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
			</section><!-- #game -->
			<aside id="chat">
<?php include("chat.tpl.php"); ?>
			</aside>
