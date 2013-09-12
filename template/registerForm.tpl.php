<?php

// wrap invalid array
if (empty($this->var['invalid'])) {
	$invalid = array();
} else {
	$invalid = $this->var['invalid'];
	$hasFocus = true;
	$this->show('_error');
}

$this->headers('_mainSectionHeader');
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
							required="required"
							pattern="<?php echo User::getUserNamePattern(''); ?>"
							<?php
echo (isset($_POST['userName']) ? " value=\"{$_POST['userName']}\"" : '');
if (empty($invalid)) {
	echo ' autofocus="autofocus" />';
} elseif (array_key_exists('userName', $invalid)) {
	echo ' class="invalid" autofocus="autofocus" /><small class="invalidReason">'
	   . $this->lang($invalid['userName'])
	   . '</small>';
	$hasFocus = false;
} else{
	echo '/>';
}						?>
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
					<input 	type="email"
							name="email"
							required="required"
							<?php
echo (isset($_POST['email']) ? " value=\"{$_POST['email']}\"" : '');
if (array_key_exists('email', $invalid)) {
	echo ' class="invalid" ';
	if ($hasFocus) {
		echo ' autofocus="autofocus"';
		$hasFocus = false;
	}
	echo '/><small class="invalidReason">'
	   . $this->lang($invalid['email'])
	   . '</small>';
} else {
	echo '/>';
}						?>
				</dd>
				<dt>
					<label for="emailConfirm"><?php echo $this->lang('user.email.confirm'); ?></label>
				</dt>
				<dd>
					<input 	type="email"
							name="emailConfirm"
							required="required"
							<?php
echo (isset($_POST['emailConfirm']) ? " value=\"{$_POST['emailConfirm']}\"" : '');
if (array_key_exists('emailConfirm', $invalid)) {
	echo ' class="invalid" ';
	if ($hasFocus) {
		echo ' autofocus="autofocus"';
		$hasFocus = false;
	}
	echo '/><small class="invalidReason">'
	   . $this->lang($invalid['emailConfirm'])
	   . '</small>';
} else {
	echo '/>';
}						?>
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
							required="required"
							pattern="<?php echo User::getPasswordPattern(''); ?>"
							<?php
echo (isset($_POST['password']) ? " value=\"{$_POST['password']}\"" : '');
if (array_key_exists('password', $invalid)) {
	echo ' class="invalid" ';
	if ($hasFocus) {
		echo ' autofocus="autofocus"';
		$hasFocus = false;
	}
	echo '/><small class="invalidReason">'
	   . $this->lang($invalid['password'])
	   . '</small>';
} else {
	echo '/>';
}						?>
				</dd>
				<dt>
					<label for="passwordConfirm"><?php echo $this->lang('user.password.confirm'); ?></label>
				</dt>
				<dd>
					<input 	type="password"
							name="passwordConfirm"
							required="required"
							<?php
echo (isset($_POST['passwordConfirm']) ? " value=\"{$_POST['passwordConfirm']}\"" : '');
if (array_key_exists('passwordConfirm', $invalid)) {
	echo ' class="invalid" ';
	if ($hasFocus) {
		echo ' autofocus="autofocus"';
		$hasFocus = false;
	}
	echo '/><small class="invalidReason">'
	   . $this->lang($invalid['passwordConfirm'])
	   . '</small>';
} else {
	echo '/>';
}						?>
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
if (isset($_POST['language'])) {
	$current = $_POST['language'];
} else {
	$current = Core::getLanguage()->getLanguageCode();
}
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
