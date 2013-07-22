<section id="registerForm" class="form">
	<header>
		<h1><?php echo $this->lang('user.register'); ?></h1>
	</header>
<?php

$errors = false; // send form in correction mode
if (!empty($this->var['invalid'])) {
	$errors = true;
	$this->show('_error');
}

?>
	<form method="post" action="<?php
		echo Util::url('User/register');
	?>">
		<fieldset>
			<legend><?php echo $this->lang('user.name'); ?></legend>
			<dl>
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
			</dl>
		</fieldset>
		<fieldset>
			<legend><?php echo $this->lang('user.email'); ?></legend>
			<dl>
				<dt>
					<label for="email"><?php echo $this->lang('user.email'); ?></label>
				</dt>
				<dd>
					<input 	type="text"
							name="email"
							<?php
if ($errors && in_array('email', $this->var['invalid'])) {
	echo ' class="invalid" ';
}
if (isset($_POST['email'])) {
	echo " value=\"{$_POST['email']}\"";
}
						?> />
				</dd>
				<dt>
					<label for="emailConfirm"><?php echo $this->lang('user.email.confirm'); ?></label>
				</dt>
				<dd>
					<input 	type="text"
							name="emailConfirm"
							<?php
if ($errors && in_array('emailConfirm', $this->var['invalid'])) {
	echo ' class="invalid" ';
}
if (isset($_POST['emailConfirm'])) {
	echo " value=\"{$_POST['emailConfirm']}\"";
}
						?> />
				</dd>
			</dl>
		</fieldset>
		<fieldset>
			<legend><?php echo $this->lang('user.password'); ?></legend>
			<dl>
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
				<dt>
					<label for="passwordConfirm"><?php echo $this->lang('user.password.confirm'); ?></label>
				</dt>
				<dd>
					<input 	type="password"
							name="passwordConfirm"
							<?php
if ($errors && in_array('passwordConfirm', $this->var['invalid'])) {
	echo ' class="invalid" ';
}
if (isset($_POST['passwordConfirm'])) {
	echo " value=\"{$_POST['passwordConfirm']}\"";
}
						?> />
				</dd>
			</dl>
		</fieldset>
		<fieldset>
			<legend><?php echo $this->lang('user.settings'); ?></legend>
			<dl>
				<dt>
					<label for="language"><?php echo $this->lang('user.settings.language'); ?></label>
				</dt>
				<dd>
					<select name="language"><?php
$langs = Language::getLanguages();
$current = Core::getLanguage()->getLanguageCode();
foreach ($langs as $code => $lang) {
	echo "<option value=\"{$code}\""
	   . ($code == $current ? ' selected' : '')
	   . ">{$lang}</option>";
}
					?></select>
				</dd>
			</dl>
		</fieldset>
		<button	type="submit" class="button"><?php
			echo $this->lang('user.register');
		?></button>
		<button	type="reset" class="button"><?php
			echo $this->lang('form.reset');
		?></button>
	</form>
</section>
