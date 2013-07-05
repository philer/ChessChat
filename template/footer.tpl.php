		</div><!-- #main -->
		<footer id="footer">
			<nav id="footerMenu">
				<ul>
					<li><a href="<?php echo Util::url('LegalNotice'); ?>"><?php
						echo $this->lang('site.legalnotice');
					?></a></li><li><a href="<?php echo Util::url('User/list'); ?>"><?php
						echo $this->lang('user.list');
					?></a></li>
				</ul>
			</nav>
			<div id="benchmark" style="float:right;">
				<span>time: <?php echo round(microtime(true) - START_TIME, 4); ?></span>
				<span>queries: <?php echo Core::getDB()->getQueryCount(); ?></span>
			</div>
			<div id="copyright"><?php echo $this->lang('site.copyrightby') ?>Phil &amp; Larissa</div>
		</footer>
<?php //include("overlay.tpl.php"); ?>
	</body>
</html>
