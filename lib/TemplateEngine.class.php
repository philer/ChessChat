<?php

/**
 * The TemplateEngine controlls all template activity.
 * It keeps track of scripts to be linked in the document head,
 * holds variables for use in templates and executes templates
 * on demand.
 * @author Philipp Miller
 */
class TemplateEngine {
	
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
	 * Executes and sends a template.
	 * @param 	string 	$template 	name of the template
	 */
	public function show($template) {
		include(ROOT_DIR."template/".$template.".tpl.php");
	}
	
	/**
	 * Includes a Template and adds relevant variables first.
	 * @param 	string 	$template 	name of the template
	 * @param 	array 	$vars 		variables to be added
	 */
	protected function includeTemplate($template, $vars = array()) {
		$this->vars = array_merge($this->vars, $vars);
		$this->show($template);
	}
	
	
	/**
	 * Adds a script that will be linked in the document head
	 * @param 	string 	$script.
	 */
	public function registerScript($script) {
		$this->scripts[] = HOST."js/".$script.".js";
	}
	
	/**
	 * Adds a script that will be linked in the document head
	 * with an 'async' attribute.
	 * @param 	string 	$script
	 */
	public function registerAsyncScript($script) {
		$this->asyncScripts[] = HOST."js/".$script.".js";
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
	 * Adds a stylesheet that will be linked in the document head
	 * @param 	string 	$stylesheet.
	 */
	public function registerStylesheet($stylesheet) {
		$this->stylesheets[] = HOST."style/".$stylesheet.".css";
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
	
	/**
	 * Returns a clean array of all stylesheets to be linked
	 * in the document head.
	 * @return 	array<string>
	 */
	public function getStylesheets() {
		return array_unique($this->stylesheets);
	}
}
