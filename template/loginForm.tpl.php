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
	<form class="form" method="post" action="<?php
		echo Util::url('User/login');
	?>">
		<fieldset>
			<legend><?php echo $this->lang('user.login'); ?></legend>
			<dl class="tableList">
				<dt>
					<label for="userName"><?php echo $this->lang('user.name'); ?></label>
				</dt>
				<dd>
					<input 	type="text"
							name="userName"
							required="required"
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
				<dt>
					<label for="password"><?php echo $this->lang('user.password'); ?></label>
				</dt>
				<dd>
					<input 	type="password"
							name="password"
							required="required"
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
