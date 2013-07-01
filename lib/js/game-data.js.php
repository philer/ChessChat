var game = {
	id       : <?php echo $this->var['game']->getId(); ?>,
	ownColor : "<?php echo $this->var['game']->isWhitePlayer() ? 'white' : 'black'; ?>"
}
