<p class="<?php
	if ($this->var['msg']->isBotMsg) echo "msgBot";
	elseif ($this->var['msg']->isown()) echo "msgOwn";
	else echo "msgOther";
?>">
	<span class="msgTime"><?php echo $this->var['msg']->getFormattedTime() ?></span>
	<span class="msgAuthor"><?php echo $this->var['msg']->authorName ?></span>
	<span class="msgText"><?php echo $this->var['msg']->messageText ?></span>
</p>
