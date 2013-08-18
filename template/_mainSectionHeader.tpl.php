<section id="<?php echo $this->page ?>">
	<header>
		<h1>
			<a href="<?php
				echo Util::url($this->controller->getCanonicalRoute())
			?>" ><?php
				echo $this->controller->getPageTitle()
			?></a>
		</h1>
	</header>
