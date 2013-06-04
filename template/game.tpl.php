<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>&#x265A; <?php echo SITENAME ?></title>
		<base href="<?php echo HOST ?>" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo HOST ?>style/fonts.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo HOST ?>style/global.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo HOST ?>style/colors.css" />
		<script src="<?php echo HOST ?>js/jquery-2.0.0.min.js"></script>
		<script src="<?php echo HOST ?>js/chessboardLayout.js"></script>
		<script src="<?php echo HOST ?>js/chat.js"></script>
		<link rel="canonical" href="<?php echo HOST ?>" />
	</head>
	<body lang="<?php //TODO ?>">
		<header id="header">
			<h1><span id="logo" class="icon">&#x265A;</span><?php echo SITENAME ?></h1>
			<nav id="panel">
				<ul>
					<li><a href="#"><?php lang('site.menu.newgame') ?></a></li
					><li><a href="#"><?php lang('site.menu.settings') ?></a></li>
				</ul>
			</nav>
		</header>
		<main>
			<section id="game">
				<header>
					<h2>Phil <span class="vs">vs</span> Larissa</h2>
					<div id="clock">
						<span id="timer">3:00</span>
						<span id="playtime">0:27:49</span>
					</div><!--#clock-->
				</header>
<?php require_once("chessboard.tpl.php"); ?>
				<footer>
					<nav id="gameMenu">
						<ul>
							<li><a href="#"><?php lang('game.menu.resign') ?></a></li
							><li><a href="#"><?php lang('game.menu.offerdraw') ?></a></li>
						</ul>
					</nav>
				</footer>
			</section><!-- #game -->
			<aside id="chat">
<?php require_once("chat.tpl.php"); ?>
			</aside>
		</main>
		<footer id="footer">
			<nav id="footerMenu">
				<ul>
					<li><a href="#"><?php lang('site.menu.legalnotice') ?></a></li
					><li><a href="#"><?php lang('site.menu.contact') ?></a></li>
				</ul>
			</nav>
			<div id="copyright"><?php lang('site.copyrightby') ?>Phil &amp; Larissa</div>
		</footer>
<?php //require_once("overlay.tpl.php"); ?>
	</body>
</html>
