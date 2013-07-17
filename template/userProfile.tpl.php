			<section id="userProfile">
				<header>
					<h1><?php echo $this->var['user']->getName(); ?></h1>
				</header>
<?php

if (!$this->var['user']->isSelf()) {
	echo '<a href="'
	   . Util::url('Game/new/?opponent=' . urlencode($this->var['user']))
	   . '">'
	   . $this->lang('game.new.against',
	   		array('opponent' => $this->var['user']))
	   . '</a>';
}

$this->show('_gameList');
?>
			</section>
