<section>
	<header>
		<h1><?php echo $this->lang('game.new'); ?></h1>
	</header>
<?php

$errors = false; // send form in correction mode
if (!empty($this->var['invalid'])) {
	$errors = true;
	$this->show('_error');
}

?>
	<form id="gameForm" method="post" action="<?php
		echo Util::url('Game/new');
	?>">
		<fieldset>
			<dl class="form">
				<dt>
					<label for="opponent">Opponent</label>
				</dt>
				<dd>
					<input 	type="text"
							name="opponent"
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
					<label for="password">White Player</label>
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
			<button	type="submit" id="loginSubmit"><?php
				echo $this->lang('form.submit');
			?></button>
		</fieldset>
	</form>
</section>
