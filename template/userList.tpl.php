			<section id="userList">
				<header>
					<h1><?php echo $this->lang('user.list'); ?></h1>
				</header>
				<ul class="userList dataList"><?php
foreach ($this->var['users'] as $user) {
					?><li>
						<dl class="userData">
							<dt class="userName heading"><?php echo $this->lang('user.name'); ?></dt>
							<dd class="userName heading"><?php echo new Link($user, $user->getRoute()); ?>
							</dd>
							<dt class="gameCount"><?php echo $this->lang('user.gameCount'); ?></dt>
							<dd class="gameCount"><?php echo $user->getGameCount(); ?>
							</dd>
						</dl>
					</li><?php
}
				?></ul>
			</section><!-- #gameList -->
