<p class="<?php
	if ($this->vars['msg']->isBotMsg) echo "msgBot";
	elseif ($this->vars['msg']->isown()) echo "msgOwn";
	else echo "msgOther";
?>">
	<span class="msgTime"><?php echo $this->vars['msg']->getFormattedTime() ?></span>
	<span class="msgAuthor"><?php echo $this->vars['msg']->authorName ?></span>
	<span class="msgText"><?php echo $this->vars['msg']->messageText ?></span>
</p>
