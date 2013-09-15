	<body lang="<?php echo $this->language->getLanguageCode(); ?>">
		<header id="header">
			<h1>
				<a href="<?php echo Util::getBaseUrl(); ?>">
					<span id="logo" class="icon">&#x265A;</span><?php echo SITENAME ?>
				</a>
			</h1>
			<nav id="panel">
				<ul id="mainMenu">
<?php
$guest = Core::getUser()->isGuest();
$menuItems = array(
	new Link('game.list', 'Game', true),
	new Link('game.new', 'Game/new', !$guest),
	new Link('user.profile', 'User', !$guest),
	new Link('user.logout', 'User/logout', !$guest),
	new Link('user.register', 'User/register', $guest),
	new Link('user.login','User/login',!QUICK_LOGIN && $guest),
);
foreach ($menuItems as $mi) {
	if ($mi->isVisible()) {
		echo "<li>{$mi}</li>";
	}
}
?>
				</ul>

<?php if (QUICK_LOGIN
          && $guest
          && $this->page !== 'loginForm') { ?>
				<form id="loginForm" method="post" action="<?php
					echo Util::url('User/login');
				?>">
					<fieldset>
						<input 	type="text"
								name="userName"
								id="loginName"
								required="required"
								placeholder="<?php echo $this->lang('user.name'); ?>"
							/>
						<input 	type="password"
								name="password"
								id="loginPassword"
								required="required"
								placeholder="<?php echo $this->lang('user.password'); ?>"
							/>
						<button	type="submit" id="loginSubmit"><?php
							echo $this->lang('user.login');
						?></button>
					</fieldset>
				</form>
<?php } ?>
			</nav>
		</header>
		<main role="main" id="main">
