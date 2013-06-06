<?php $this->includeTemplate("head"); ?>

<?php $this->includeTemplate("header"); ?>

		<div id="main">
			<section>

				<header>
					<h2><?php lang("site.legalnotice"); ?></h2>
				</header>
				<dl>
					<dt><?php lang("legal.nameAndAddress") ?></dt>
					<dd>
						<?php echo $this->vars['legal']['name'] ?>
						<br />
						<?php echo $this->vars['legal']['addr'] ?>
					</dd>
					<dt><?php lang("legal.email") ?></dt>
					<dd><?php echo $this->vars['legal']['email'] ?></dd>
				</dl>

			</section>
		</div><!-- #main -->
		
<?php $this->includeTemplate("footer"); ?>
