var gameData = {
	id         : <?php echo $this->game->getId(); ?>,

<?php if ($this->game->isPlayer()) { ?>
	ownColor   : "<?php echo $this->game->isWhitePlayer() ? 'white' : 'black'; ?>",
<?php } ?>

	lastMoveId : <?php echo $this->game->getLastMoveId(); ?>
}

var chatData = {
	lastMsgId : <?php
		if ($lastMsg = end($this->var['chatMsgs'])) {
			echo $lastMsg->messageId;
		} else {
			echo 0;
		}
		?>
}
