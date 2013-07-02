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
						echo $this->url('Game/new');
					?>"><?php
						echo $this->lang('site.menu.newgame')
					?></a></li><li><a href="<?php
						echo $this->url('User/edit');
					?>"><?php
						echo $this->lang('site.menu.settings')
					?></a></li>
				</ul>
			</nav>
		</header>
		<div id="main">
