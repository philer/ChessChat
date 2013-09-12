<?php
$this->headers('_mainSectionHeader');
?>				<dl>
					<dt><?php echo $this->lang('legal.nameAndAddress') ?></dt>
					<dd>
						<?php echo $this->var['legal']['name'] ?>
						<br />
						<?php echo $this->var['legal']['addr'] ?>
					</dd>
					<dt><?php echo $this->lang("legal.email") ?></dt>
					<dd>
						<a href="mailto:<?php echo $this->var['legal']['email'] ?>">
							<?php echo $this->var['legal']['email'] ?>
						</a>
					</dd>
<?php
	if (!empty($this->var['legal']['phone'])) {
		echo "<dt>{$this->lang('legal.phone')}</dt>"
			."<dd>{$this->var['legal']['phone']}</dd>\n";
	}
	if (!empty($this->var['legal']['fax'])) {
		echo "<dt>{$this->lang('legal.fax')}</dt>"
			."<dd>{$this->var['legal']['fax']}</dd>\n";
	}
	if (!empty($this->var['legal']['representatives'])) {
		echo "<dt>{$this->lang('legal.representatives')}</dt>"
			."<dd>{$this->var['legal']['representatives']}</dd>\n";
	}
	/*if (!empty($this->var['legal']['taxID'])) {
		echo "<dt>{$this->lang('legal.taxID')}</dt>"
			."<dd>{$this->var['legal']['taxID']}</dd>\n";
	}*/
?>
				</dl>
			</section>
