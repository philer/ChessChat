<?php $this->includeTemplate("head"); ?>

<?php $this->includeTemplate("header"); ?>

		<div id="main">
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
							<li><a href="#"><?php echo lang('game.menu.resign') ?></a></li
							><li><a href="#"><?php echo lang('game.menu.offerdraw') ?></a></li>
						</ul>
					</nav>
				</footer>
			</section><!-- #game -->
			<aside id="chat">
				
<?php include("chat.tpl.php"); ?>

			</aside>
		</div><!-- #main -->
		
<?php $this->includeTemplate("footer"); ?>
