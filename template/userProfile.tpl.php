<?php
$this->show('_mainSectionHeader');

if (!$this->var['user']->isSelf()) {
	echo '<a href="'
	   . Util::url('Game/new/?opponent=' . urlencode($this->var['user']))
	   . '">'
	   . $this->lang('game.new.against', array('opponent' => $this->var['user']))
	   . '</a>';
}

$this->show('_gameList');
?>			</section>
