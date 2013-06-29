	<body lang="<?php echo $this->language->getLanguageCode(); ?>">
		<header id="header">
			<h1>
				<a href="index.php">
					<span id="logo" class="icon">&#x265A;</span><?php echo SITENAME ?>
				</a>
			</h1>
			<nav id="panel">
				<ul>
					<li><a href="<?php
						echo $this->url('Game');
					?>"><?php
						echo $this->lang('site.menu.gamelist')
					?></a></li><li><a href="<?php
						echo $this->url('Game/new');
					?>"><?php
						echo $this->lang('site.menu.newgame')
					?></a></li><li><a href="<?php
						echo $this->url('User/edit');
					?>"><?php
						echo $this->lang('site.menu.settings')
					?></a></li>
				</ul>
<?php if (Core::getUser()->guest()) { if (QUICK_LOGIN) { ?>
				<form id="loginForm" method="post" action="<?php
					echo $this->url('User/login');
				?>">
					<fieldset>
						<input type="hidden" name="userId" value="3" />
						<input 	type="text"
								name="userName"
								id="loginName"
							/>
						<input 	type="password"
								name="password"
								id="loginPassword"
							/>
						<button	type="submit" id="loginSubmit"><?php
							echo $this->lang('login');
						?></button>
					</fieldset>
				</form>
<?php } } else { ?>
				<div id="userInfo">
					<span>Logged in as <a href="<?php
						echo $this->url('User/logout');
					?>"><?php
						echo Core::getUser();
					?></a></span>
				</div>
<?php } ?>
			</nav>
		</header>
		<div id="main">
