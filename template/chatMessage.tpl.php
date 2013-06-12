<p class="<?php echo (isset($this->vars['bot'])) ? "msgBot" : "msgOwn"; ?>">
	<span class="msgTime"><?php echo date('G:i',NOW) ?></span>
	<span class="msgAuthor">
		<?php echo (isset($this->vars['bot'])) ? $this->vars['bot'] : Core::getUser(); ?>
	</span>
	<span class="msgText"><?php echo $this->vars['msg'] ?></span>
</p>
