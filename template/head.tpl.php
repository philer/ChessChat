<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>&#x265A; <?php echo SITENAME ?></title>
		<base href="<?php echo HOST ?>" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo HOST ?>style/fonts.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo HOST ?>style/global.css" />
		<link rel="stylesheet" type="text/css" media="screen" href="<?php echo HOST ?>style/colors.css" />
		<script src="<?php echo HOST ?>js/jquery-2.0.0.min.js"></script>
<?php
	foreach ($this->getAsyncScripts() as $script) {
		echo "<script type=\"text/javascript\" src=\"".$script."\" async ></script>\n";
	}
	foreach ($this->getScripts() as $script) {
		echo "<script type=\"text/javascript\" src=\"".$script."\"></script>\n";
	}
	foreach ($this->getDynamicScripts() as $script) {
		echo "<script type=\"text/javascript\" >\n//<![CDATA[\n";
		include($script);
		echo "\n//]]>\n</script>\n";
	}
	foreach ($this->getStylesheets() as $stylesheet) {
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$stylesheet."\" />\n";
	}
?>
		<link rel="canonical" href="<?php //TODO ?>" />
	</head>
	<body lang="<?php echo Core::getLanguage()->getLanguageCode(); ?>">
