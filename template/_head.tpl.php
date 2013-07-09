<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>&#x265A; <?php echo SITENAME // TODO pageTitle ?></title>
		<base href="<?php echo HOST ?>" />
		<?php //TODO<link rel="canonical" href="" />?>
<?php
	foreach ($this->getStylesheets() as $stylesheet) {
		echo "\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"{$stylesheet}\" />\n";
	}
	foreach ($this->getScripts() as $script) {
		echo "\t\t<script type=\"text/javascript\" src=\"{$script}\"></script>\n";
	}
	foreach ($this->getAsyncScripts() as $script) {
		echo "\t\t<script type=\"text/javascript\" src=\"{$script}\" async ></script>\n";
	}
	foreach ($this->getDynamicScripts() as $script) {
		echo "\t\t<script type=\"text/javascript\" >//<![CDATA[\n";
		include($script);
		echo "//]]></script>\n";
	}
?>
	</head>
