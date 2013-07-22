<section id="loginForm" class="form">
	<header>
		<h1><?php echo $this->lang('user.login'); ?></h1>
	</header>
<?php

$errors = false; // send form in correction mode
if (!empty($this->var['invalid'])) {
	$errors = true;
	$this->show('_error');
}

?>
	<form method="post" action="<?php
		echo Util::url('User/login');
	?>">
		<fieldset>
			<legend><?php echo $this->lang('user.login'); ?></legend>
			<dl class="form">
				<dt>
					<label for="userName"><?php echo $this->lang('user.name'); ?></label>
				</dt>
				<dd>
					<input 	type="text"
							name="userName"
							<?php
if ($errors && in_array('userName', $this->var['invalid'])) {
	echo ' class="invalid" ';
}
if (isset($_POST['userName'])) {
	echo " value=\"{$_POST['userName']}\"";
}
						?> />
				</dd>
				<dt>
					<label for="password"><?php echo $this->lang('user.password'); ?></label>
				</dt>
				<dd>
					<input 	type="password"
							name="password"
							<?php
if ($errors && in_array('password', $this->var['invalid'])) {
	echo ' class="invalid" ';
}
if (isset($_POST['password'])) {
	echo " value=\"{$_POST['password']}\"";
}
						?> />
				</dd>
			</dl>
		</fieldset>
		<button	type="submit" class="button"><?php
			echo $this->lang('user.login');
		?></button>
		<button	type="reset" class="button"><?php
			echo $this->lang('form.reset');
		?></button>
	</form>
</section>
