<?php

$errors = false; // send form in correction mode
if (!empty($this->var['invalid'])) {
	$errors = true;
	$this->show('_error');
}

$this->headers('_mainSectionHeader');
?>
	<form method="post" action="<?php
		echo Util::url('Game/new');
	?>">
		<fieldset>
			<legend><?php echo $this->lang('game.settings'); ?></legend>
			<dl class="form">
				<dt>
					<label for="opponent"><?php echo $this->lang('game.opponent'); ?></label>
				</dt>
				<dd>
					<input 	type="text"
							name="opponent"
							required="required"
							autofocus="autofocus"
							<?php
if ($errors && in_array('opponent', $this->var['invalid'])) {
	echo ' class="invalid" ';
}
if (isset($_POST['opponent'])) {
	echo " value=\"{$_POST['opponent']}\"";
}
if (isset($_GET['opponent'])) {
	echo " value=\"{$_GET['opponent']}\"";
}
					?> />
				</dd>
				<dt>
					<label for="password"><?php echo $this->lang('game.whiteplayer'); ?></label>
				</dt>
				<dd>
					<label>
						<input 	type="radio"
								name="whitePlayer"
								value="self"
								<?php
if (!isset($_POST['whitePlayer']) || $_POST['whitePlayer'] === 'self') {
	echo 'checked="checked"';
}
							?> />
						<?php echo $this->lang('me'); ?>
					</label>
					<label>
						<input 	type="radio"
								name="whitePlayer"
								value="other"
								<?php
if (isset($_POST['whitePlayer']) && $_POST['whitePlayer'] === 'other') {
	echo 'checked="checked"';
}
							?> />
						<?php echo $this->lang('opponent'); ?>
					</label>
				</dd>
			</dl>
		</fieldset>
		<button	type="submit" class="button"><?php
			echo $this->lang('form.submit');
		?></button>
		<button	type="reset" class="button"><?php
			echo $this->lang('form.reset');
		?></button>
	</form>
</section>
