			<section>
				<header>
					<h1><?php echo lang('site.legalnotice'); ?></h1>
				</header>
				<dl>
					<dt><?php echo lang('legal.nameAndAddress') ?></dt>
					<dd>
						<?php echo $this->vars['legal']['name'] ?>
						<br />
						<?php echo $this->vars['legal']['addr'] ?>
					</dd>
					<dt><?php echo lang("legal.email") ?></dt>
					<dd>
						<a href="mailto:<?php echo $this->vars['legal']['email'] ?>">
							<?php echo $this->vars['legal']['email'] ?>
						</a>
					</dd>
<?php
	if (!empty($this->vars['legal']['phone'])) {
		echo "<dt>".lang('legal.phone')."</dt>\n"
			."<dd>".$this->vars['legal']['phone']."</dd>";
	}
	if (!empty($this->vars['legal']['fax'])) {
		echo "<dt>".lang('legal.fax')."</dt>\n"
			."<dd>".$this->vars['legal']['fax']."</dd>";
	}
	if (!empty($this->vars['legal']['representatives'])) {
		echo "<dt>".lang('legal.representatives')."</dt>\n"
			."<dd>".$this->vars['legal']['representatives']."</dd>";
	}
	/*if (!empty($this->vars['legal']['taxID'])) {
		echo "<dt>".lang('legal.taxID')."</dt>\n"
			."<dd>".$this->vars['legal']['taxID']."</dd>";
	}*/
?>
				</dl>
			</section>
