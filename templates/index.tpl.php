<!DOCTYPE html>
<html dir="ltr" lang="de">
	<head>
		<title>&#x265A; ChessChat</title>
		<base href="_blank" />
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" media="screen" href="style/global.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="style/colors.css" />
		<script src="js/jquery-2.0.0.min.js"></script>
		<script src="js/chessboardLayout.js"></script>
		<script src="js/chat.js"></script>
		<style id="jscss"></style>
	</head>
	<body>
		<header id="header">
			<h1><span class="icon">&#x265A;</span>ChessChat</h1>
			<nav id="panel">
				<ul>
					<li><a href="#">New Game</a></li><li><a href="#">Settings</a></li>
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
							<li><a href="#">Resign</a></li><li><a href="#">Offer draw</a></li><li><a href="#">Snapshot</a></li>
						</ul>
					</nav>
				</footer>
			</section><!-- #game -->
			<aside id="chat">
				<section>
					<div id="chatLog">
						<p class="msgOwn">
							<span class="msgTime">17:13</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText">Checkmate :D</span>
						</p>
						<p class="msgOther">
							<span class="msgTime">21:13</span>
							<span class="msgAuthor">Larissa</span>
							<span class="msgText">oh noes!</span>
						</p>
						<p class="msgBot">
							<span class="msgTime">21:14</span>
							<span class="msgAuthor">Phil </span>
							<span class="msgText">(&#x2657;E3) Bishop from C2 to E3</span>
						</p>
						<p class="msgOwn">
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText">More text and more text and even more text and this is so much text it just HAS to break the line</span>
						</p>
						<p class="msgOther">
							<span class="msgTime">02:00</span>
							<span class="msgAuthor">Larissa</span>
							<span class="msgText">That's a lot of text</span>
						</p>
						<p class="msgOther">
							<span class="msgTime">02:56</span>
							<span class="msgAuthor">Larissa</span>
							<span class="msgText">I'll make some more text, too.</span>
						</p>
						<p class="msgBot">
							<span class="msgTime">21:14</span>
							<span class="msgAuthor">Larissa </span>
							<span class="msgText">moved Queen &#x265B; from D8 to D5</span>
						</p>
						<p class="msgOwn">
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText">
obligatory features<br />
- ajax chat<br />
- move via chat msg ("QB4")<br />
- server-side move validation<br />
- user system / url identification
							</span>
						</p>
						<p class="msgOwn">
							<span class="msgTime">21:15</span>
							<span class="msgAuthor">Phil</span>
							<span class="msgText">
optional features<br />
- move via drag & drop (JS)<br />
- client-side validation (JS)<br />
- saving games (MySQL)<br />
- possible moves highlight (JS)<br />
- clock
							</span>
						</p>
					</div><!-- #chatLog -->
				</section>
				<form id="chatForm" action="">
					<fieldset>
						<div>
							<input 	type="text"
									name="chatText"
									id="chatText"
									autofocus="autofocus"
									autocomplete="off"
								/>
						</div>
						<input 	type="submit" 
								name="submit"
								id="chatSubmit"
								value="Send"
							/>
					</fieldset>
				</form>
			</aside>
		</main>
		<footer id="footer">
			<nav id="footerMenu">
				<ul>
					<li><a href="#">Impress</a></li><li><a href="#">Contact</a></li>
				</ul>
			</nav>
			<div id="copyright">© by Phil & Larissa</div>
		</footer>
		<div class="overlay">
			<div class="overlayContainer">
				<h3>Overlay Title</h3>
				<p>Some fancy overlay text<br /><br /><br /></p>
				<p>Some longer fancy overlay text<br /><br /><br /></p>
				<p>Some fancy overlay text<br /><br /><br /></p>
				<p>Some longer fancy overlay text. OMG this text is so long its incredibly long so very very very long, omg so long.<br /><br /><br /></p>
				<p>Some fancy overlay text<br /><br /><br /></p>
				<p>Some fancy overlay text<br /><br /><br /></p>
				<p>Some fancy overlay text<br /><br /><br /></p>
				<p>Some fancy overlay text<br /><br /><br /></p>
				<p>Some fancy overlay text<br /><br /><br /></p>
				<p>Some fancy overlay text<br /><br /><br /></p>
			</div>
		</div>
	</body>
</html>
