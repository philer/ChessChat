	<body lang="<?php echo $this->language->getLanguageCode(); ?>">
		<header id="header">
			<h1>
				<a href="index.php">
					<span id="logo" class="icon">&#x265A;</span><?php echo SITENAME ?>
				</a>
			</h1>
			<nav id="panel">
				<ul id="mainMenu">
<?php
$guest = Core::getUser()->guest();
$menuItems = array(
	array(
		'name'  => $this->lang('game.list'),
		'route' => Util::url('Game'),
		'show'  => true,
	),
	array(
		'name'  => $this->lang('game.new'),
		'route' => Util::url('Game/new'),
		'show'  => !$guest,
	),
	array(
		'name'  => $this->lang('user.profile'),
		'route' => Util::url('User'),
		'show'  => !$guest,
	),
	array(
		'name'  => $this->lang('user.logout'),
		'route' => Util::url('User/logout'),
		'show'  => !$guest,
	),
	array(
		'name'  => $this->lang('user.register'),
		'route' => Util::url('User/register'),
		'show'  => $guest,
	),
	array(
		'name'  => $this->lang('user.login'),
		'route' => Util::url('User/login'),
		'show'  => !QUICK_LOGIN,
	),
);
foreach ($menuItems as $mi) {
	if ($mi['show']) {
		echo "<li><a href=\"{$mi['route']}\">{$mi['name']}</a></li>";
	}
}
?>
				</ul>

<?php if (QUICK_LOGIN && Core::getUser()->guest()) { ?>
				<form id="loginForm" method="post" action="<?php
					echo Util::url('User/login');
				?>">
					<fieldset>
						<input type="hidden" name="userId" value="3" />
						<input 	type="text"
								name="userName"
								id="loginName"
							/>
						<input 	type="password"
								name="password"
								id="loginPassword"
							/>
						<button	type="submit" id="loginSubmit"><?php
							echo $this->lang('login');
						?></button>
					</fieldset>
				</form>
<?php } ?>

			</nav>
		</header>
		<div id="main">
