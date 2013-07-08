			<section id="userList">
				<header>
					<h1><?php echo $this->lang('user.list'); ?></h1>
				</header>
				<ul class="userList dataList"><?php
foreach ($this->var['users'] as $user) {
					?><li>
						<dl class="userData">
							<dt class="userName"><?php echo $this->lang('user.name'); ?></dt>
							<dd class="userName"><?php echo new Link($user, $user->getRoute()); ?>
							</dd>
						</dl>
					</li><?php
}
				?></ul>
			</section><!-- #gameList -->
