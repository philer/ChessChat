<?php

/**
 * The TemplateEngine controlls all template activity.
 * It keeps track of scripts to be linked in the document head,
 * holds variables for use in templates and executes templates
 * on demand.
 * @author Philipp Miller
 */
final class TemplateEngine {
	
	/**
	 * Array contains all JavaScript files to be linked
	 * @var array<string>
	 */
	protected $scripts = array();
	
	/**
	 * Array contains all JavaScript files to be linked
	 * with an 'async' attribute
	 * @var array<string>
	 */
	protected $asyncScripts = array();
	
	/**
	 * Array contains all JavaScript files to be included
	 * directly after executing php code.
	 * @var array<string>
	 */
	protected $dynamicScripts = array();
	
	/**
	 * Array contains all stylesheet files to be linked
	 * @var array<string>
	 */
	protected $stylesheets = array();
	
	/**
	 * Contains all necessarry, non-global variables
	 * for registered templates.
	 * @var array<mixed>
	 */
	protected $vars = array();
	
	/**
	 * Add a variable to be used in template.
	 * @param 	string 	$key
	 * @param 	mixed 	$value
	 */
	public function addVar($key, $value) {
		$this->vars[$key] = $value;
	}
	
	/**
	 * Executes and sends a template. If $full is true
	 * includes header and footer templates.
	 * @param 	string 	$template 	name of the template
	 * @param 	boolean	$full 		include header and footer templates?
	 */
	public function show($template, $full = false) {
		if ($full) {
			include(ROOT_DIR."template/head.tpl.php");
			flush(); // we can send the header right away
			include(ROOT_DIR."template/header.tpl.php");
			include(ROOT_DIR."template/".$template.".tpl.php");
			include(ROOT_DIR."template/footer.tpl.php");
			flush(); // make sure everything gets there
		} else {
			include(ROOT_DIR."template/".$template.".tpl.php");
		}
	}
	
	/**
	 * Executes a template and returns the result without sending it.
	 * @param 	string 	$template 	name of the template
	 * @return 	string
	 */
	public function fetch($template) {
		ob_start();
		$this->show($template, false);
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}
	
	/**
	 * Includes a Template and adds relevant variables first.
	 * @param 	string 	$template 	name of the template
	 * @param 	array 	$vars 		variables to be added
	 */
	protected function includeTemplate($template, $vars = array()) {
		$this->vars = array_merge($this->vars, $vars);
		$this->show($template, false);
	}
	
			
	/**
	 * Adds a stylesheet that will be linked in the document head
	 * @param 	string 	$stylesheet.
	 */
	public function registerStylesheet($stylesheet) {
		$this->stylesheets[] = self::getStylesheetPath($stylesheet);
	}
	
	/**
	 * Adds a script that will be linked in the document head
	 * @param 	string 	$script.
	 */
	public function registerScript($script) {
		$this->scripts[] = self::getScriptPath($script);
	}
	
	/**
	 * Adds a script that will be linked in the document head
	 * with an 'async' attribute.
	 * @param 	string 	$script
	 */
	public function registerAsyncScript($ascript) {
		$this->asyncScripts[] = self::getScriptPath($ascript);
	}
	
	/**
	 * Adds a script that will included directly in the document head
	 * after executing php code.
	 * @param 	string 	$script
	 */
	public function registerDynamicScript($script) {
		$this->dynamicScripts[] = ROOT_DIR."lib/js/".$script.".js.php";
	}
	
	
	/**
	 * Checks which file is available and returns the full path
	 * prefering compressed scripts
	 * @param 	string 	$script
	 * @return 	string
	 */
	protected static function getScriptPath($script) {
		//if (file_exists(ROOT_DIR."js/".$script.".min.js")) {return HOST."js/".$script.".min.js";} else
		if (file_exists(ROOT_DIR."js/".$script.".js")) {
			return HOST."js/".$script.".js";
		} elseif (file_exists(ROOT_DIR."js/".$script)) {
			return HOST."js/".$script.".js";
		}
	}
	
	/**
	 * Checks which file is available and returns the full path
	 * prefering compressed scripts
	 * @param 	string 	$stylesheet
	 * @return 	string
	 */
	protected static function getStylesheetPath($stylesheet) {
		//if (file_exists(ROOT_DIR."style/".$stylesheet.".min.css")) {return HOST."style/".$stylesheet.".min.css";} else
		if (file_exists(ROOT_DIR."style/".$stylesheet.".css")) {
			return HOST."style/".$stylesheet.".css";
		} elseif (file_exists(ROOT_DIR."style/".$stylesheet)) {
			return HOST."style/".$stylesheet.".css";
		}
	}
	
	
	/**
	 * Returns a clean array of all stylesheets to be linked
	 * in the document head.
	 * @return 	array<string>
	 */
	public function getStylesheets() {
		return array_unique($this->stylesheets);
	}
	
	/**
	 * Returns a clean array of all scripts to be linked
	 * in the document head.
	 * @return 	array<string>
	 */
	public function getScripts() {
		return array_unique($this->scripts);
	}
	
	/**
	 * Returns a clean array of all scripts to be linked
	 * in the document head with an 'async' attribute.
	 * @return 	array<string>
	 */
	public function getAsyncScripts() {
		return array_unique($this->asyncScripts);
	}
	
	/**
	 * Returns a clean array of all scripts to be included directly
	 * in the document head after executing php code.
	 * @return 	array<string>
	 */
	public function getDynamicScripts() {
		return array_unique($this->dynamicScripts);
	}
	
}
