<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>&#x265A; <?php echo SITENAME ?></title>
		<base href="<?php echo HOST ?>" />
		<?php //TODO<link rel="canonical" href="" />?>
		
		<link href="<?php echo HOST ?>style/global.css" rel="stylesheet" type="text/css" media="screen" />
		<script src="<?php echo HOST ?>js/jquery-2.0.0.min.js"></script>
		<script src="<?php echo HOST ?>js/jquery-ui-1.10.3.custom.min.js"></script>
<?php
	foreach ($this->getStylesheets() as $stylesheet) {
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"".$stylesheet."\" />\n";
	}
	foreach ($this->getScripts() as $script) {
		echo "<script type=\"text/javascript\" src=\"".$script."\"></script>\n";
	}
	foreach ($this->getAsyncScripts() as $script) {
		echo "<script type=\"text/javascript\" src=\"".$script."\" async ></script>\n";
	}
	foreach ($this->getDynamicScripts() as $script) {
		echo "<script type=\"text/javascript\" >\n//<![CDATA[\n";
		include($script);
		echo "\n//]]>\n</script>\n";
	}
?>
	</head>
	<body lang="<?php echo Core::getLanguage()->getLanguageCode(); ?>">
