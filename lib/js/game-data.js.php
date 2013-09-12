var gameData = {
	id       : <?php echo $this->var['game']->getId(); ?>,
<?php if ($this->var['game']->isPlayer()) { ?>
	ownColor : "<?php echo $this->var['game']->isWhitePlayer() ? 'white' : 'black'; ?>"
<?php } ?>
}

var chatData = {
	updateInterval : <?php echo CHAT_UPDATE_INTERVAL*1000 ?>,
	lastId         : <?php
		if ($lastMsg = end($this->var['chatMsgs'])) {
			echo $lastMsg->messageId;
		} else {
			echo 0;
		}
		?>
}
