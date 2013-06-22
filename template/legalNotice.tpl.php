			<section>
				<header>
					<h1><?php echo $this->lang('site.legalnotice'); ?></h1>
				</header>
				<dl>
					<dt><?php echo $this->lang('legal.nameAndAddress') ?></dt>
					<dd>
						<?php echo $this->vars['legal']['name'] ?>
						<br />
						<?php echo $this->vars['legal']['addr'] ?>
					</dd>
					<dt><?php echo $this->lang("legal.email") ?></dt>
					<dd>
						<a href="mailto:<?php echo $this->vars['legal']['email'] ?>">
							<?php echo $this->vars['legal']['email'] ?>
						</a>
					</dd>
<?php
	if (!empty($this->vars['legal']['phone'])) {
		echo "<dt>{$this->lang('legal.phone')}</dt>"
			."<dd>{$this->vars['legal']['phone']}</dd>\n";
	}
	if (!empty($this->vars['legal']['fax'])) {
		echo "<dt>{$this->lang('legal.fax')}</dt>"
			."<dd>{$this->vars['legal']['fax']}</dd>\n";
	}
	if (!empty($this->vars['legal']['representatives'])) {
		echo "<dt>{$this->lang('legal.representatives')}</dt>"
			."<dd>{$this->vars['legal']['representatives']}</dd>\n";
	}
	/*if (!empty($this->vars['legal']['taxID'])) {
		echo "<dt>{$this->lang('legal.taxID')}</dt>"
			."<dd>{$this->vars['legal']['taxID']}</dd>\n";
	}*/
?>
				</dl>
			</section>
