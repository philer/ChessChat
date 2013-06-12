		<footer id="footer">
			<nav id="footerMenu">
				<ul>
					<li><a href="index.php/LegalNotice"><?php echo lang('site.legalnotice') ?></a></li>
				</ul>
			</nav>
			<div style="float:right;">
				<?php echo round(microtime(true) - START_TIME, 4); ?>
			</div>
			<div id="copyright"><?php echo lang('site.copyrightby') ?>Phil &amp; Larissa</div>
		</footer>
<?php //include("overlay.tpl.php"); ?>
	</body>
</html>
