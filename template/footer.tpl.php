		</div><!-- #main -->
		<footer id="footer">
			<nav id="footerMenu">
				<ul>
					<li><a href="<?php echo $this->url('LegalNotice'); ?>"><?php echo $this->lang('site.legalnotice') ?></a></li>
				</ul>
			</nav>
			<div style="float:right;"><?php echo round(microtime(true) - START_TIME, 4); ?></div>
			<div id="copyright"><?php echo $this->lang('site.copyrightby') ?>Phil &amp; Larissa</div>
		</footer>
<?php //include("overlay.tpl.php"); ?>
	</body>
</html>
