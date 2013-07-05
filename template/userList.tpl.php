			<section id="userList">
				<header>
					<h1><?php echo $this->lang('user.list'); ?></h1>
				</header>
				<ul class="userList dataList"><?php

foreach ($this->var['users'] as $user) {

					?><li>
						<dl class="userData">
							<dt class="userName"><?php echo $this->lang('user.name'); ?></dt>
							<dd class="userName">
								<a href="<?php
									echo Util::url($user->getRoute());
								?>">
									<?php echo $user->getName() ?>
								</a>
							</dd>
						</dl>
					</li><?php

}

				?></ul>
			</section><!-- #gameList -->
