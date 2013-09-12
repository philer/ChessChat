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
	 * The selected Language
	 * @var Language
	 */
	protected $language = null;
	
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
	protected $var = array();
	
	/**
	 * Controller that handles the current request.
	 * @var RequestController
	 */
	protected $controller = null;
	
	/**
	 * Name of the (full page) template that was requested
	 * @see TemplateEngine::showPage()
	 * @var string
	 */
	protected $page = '';
	
	/**
	 * Initializes this TemplateEngine
	 * @param 	Language 	$language 	The language to be used
	 */
	public function __construct(Language $language) {
		$this->language = $language;
	}
	
	/**
	 * Add a variable to be used in template.
	 * @param 	string 	$key
	 * @param 	mixed 	$value
	 */
	public function addVar($key, $value) {
		$this->var[$key] = $value;
	}
	
	/**
	 * Alias function for easy use in templates,
	 * returns the appropriate value for a language variable.
	 * @param  string		$langVar
	 * @param  array<mixed>	$params
	 * @return string
	 */
	protected function lang($langVar, array $params = null) {
		return $this->language->getLanguageItem($langVar, $params);
	}
	
	/**
	 * Executes and sends a template.
	 * @param 	string 	$template 	name of the template
	 */
	public function show($template) {
		include(ROOT_DIR . 'template/' . $template . '.tpl.php');
	}
	
	/**
	 * Executes and sends a template as
	 * a full page with header and footer.
	 * @param 	string 	$template 	name of the template
	 */
	public function showPage($template, $controller) {
		$this->page       = $template;
		$this->controller = $controller;
		
		$this->show($template);
		$this->show('_footer');
		// make sure everything gets there as quickly as possible
		flush();
	}
	
	/**
	 * Executes a template and returns the result without sending it.
	 * @param 	string 	$template 	name of the template
	 * @return 	string
	 */
	public function fetch($template) {
		ob_start();
		$this->show($template);
		$result = ob_get_contents();
		ob_end_clean();
		return $result;
	}
	
	/**
	 * Sends html head and header. Uses flushing.
	 * Specify comma separated list of additional templates
	 * to be included in that order _after_ the initial headers.
	 * @param  string $additionalHeaders
	 */
	public function headers($additionalHeaders = '') {
		$this->show('_head');
		flush();
		$this->show('_header');
		if ($additionalHeaders != '') {
			$additionalHeaders = explode(',', $additionalHeaders);
			foreach ($additionalHeaders as $header) {
				$this->show(trim($header));
			}
		}
	}
	
	/**
	 * Registered scripts and stylesheets that are globally accessible
	 */
	public function registerDefaultScripts() {
		$this->registerScript('jquery-2.0.0.min');
		$this->registerScript('jquery-ui-1.10.3.custom.min');
		// $this->registerDynamicScript('user-data');
		$this->registerStylesheet('global');
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
		$this->dynamicScripts[] = ROOT_DIR . "lib/js/" . $script . ".js.php";
	}
	
	
	/**
	 * Checks which file is available and returns the full path
	 * prefering compressed scripts
	 * @param 	string 	$script
	 * @return 	string
	 */
	protected static function getScriptPath($script) {
		if (file_exists(ROOT_DIR . "js/" . $script . ".js")) {
			return Util::getBaseUrl() . "js/" . $script . ".js";
		} elseif (file_exists(ROOT_DIR . "js/" . $script)) {
			return Util::getBaseUrl() . "js/" . $script;
		}
	}
	
	/**
	 * Checks which file is available and returns the full path
	 * prefering compressed scripts
	 * @param 	string 	$stylesheet
	 * @return 	string
	 */
	protected static function getStylesheetPath($stylesheet) {
		if (file_exists(ROOT_DIR . "style/" . $stylesheet . ".css")) {
			return Util::getBaseUrl() . "style/" . $stylesheet . ".css";
		} elseif (file_exists(ROOT_DIR . "style/" . $stylesheet)) {
			return Util::getBaseUrl() . "style/" . $stylesheet;
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
