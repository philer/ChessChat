<p class="<?php
    if ($this->var['msg']->isBotMsg) echo 'botMsg';
    elseif ($this->var['msg']->isown()) echo 'ownMsg';
    else echo 'oppMsg';
?>">
    <span class="msgTime"><?php echo $this->var['msg']->getFormattedTime() ?></span>
    <span class="msgAuthor"><?php echo $this->var['msg']->authorName ?></span>
    <span class="msgText"><?php echo $this->var['msg']->messageText ?></span>
</p>
