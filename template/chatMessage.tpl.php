<p class="<?php
echo (isset($this->vars['bot'])) ? "msgBot" : "msgOwn";
?>"><?php
	?><span class="msgTime"><?php echo date('G:i',NOW) ?></span><?php
	?><span class="msgAuthor"><?php
echo (isset($this->vars['bot'])) ? $this->vars['bot'] : Core::getUser();
	?></span><?php
	?><span class="msgText"><?php echo $this->vars['msg'] ?></span><?php
?></p>
