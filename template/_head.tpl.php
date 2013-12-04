<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title><?php echo $this->controller->getPageTitle() . " - " . SITENAME; ?></title>
		<base href="<?php echo Util::getBaseUrl() ?>" />
		<link rel="canonical" href="<?php echo Util::url($this->controller->getCanonicalRoute()) ?>" />

<?php
	foreach ($this->getStylesheets() as $stylesheet) {
		echo "\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"{$stylesheet}\" />\n";
	}
	foreach ($this->getAsyncScripts() as $script) {
		echo "\t\t<script type=\"text/javascript\" src=\"{$script}\" async ></script>\n";
	}
	foreach ($this->getScripts() as $script) {
		echo "\t\t<script type=\"text/javascript\" src=\"{$script}\"></script>\n";
	}
	foreach ($this->getDynamicScripts() as $script) {
		echo "\t\t<script type=\"text/javascript\" >//<![CDATA[\n";
		include($script);
		echo "//]]></script>\n";
	}
?>
		<link rel="icon" href="<?php echo Util::getBaseUrl() ?>style/images/favicon.ico" />
	</head>
