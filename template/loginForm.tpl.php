<section id="loginForm" class="form">
	<header>
		<h1><?php echo $this->lang('user.login'); ?></h1>
	</header>
<?php

// wrap invalid array
if (empty($this->var['invalid'])) {
	$invalid = array();
} else {
	$invalid = $this->var['invalid'];
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
echo (isset($_POST['userName']) ? " value=\"{$_POST['userName']}\"" : '');
if (array_key_exists('userName', $invalid)) {
	echo ' class="invalid" /><small class="invalidReason">'
	   . $this->lang($invalid['userName'])
	   . '</small>';
} else {
	echo '/>';
}						?>
				</dd>
				<dt>
					<label for="password"><?php echo $this->lang('user.password'); ?></label>
				</dt>
				<dd>
					<input 	type="password"
							name="password"
							<?php
echo (isset($_POST['password']) ? " value=\"{$_POST['password']}\"" : '');
if (array_key_exists('password', $invalid)) {
	echo ' class="invalid" /><small class="invalidReason">'
	   . $this->lang($invalid['password'])
	   . '</small>';
} else {
	echo '/>';
}						?>
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
