<?php

class Language {
	
	/**
	 * abbreviation for this language
	 * as used by request headers etc.
	 * @var 	string
	 */
	protected $abbr;
	
	/**
	 * name of language in this language
	 * @var 	string
	 */
	protected $name;
	
	/**
	 * all languages known by this system
	 * @var 	array
	 */
	protected static $languages;
	
	/**
	 * all language variables of this language
	 * @var 	array
	 */
	protected $langVars;
	
	/**
	 * global language variables are the same in all
	 * languages
	 * @var 	array
	 */
	protected static $globalLangVars;
	
	/**
	 * Loads the appropriate language files.
	 * @param 	string 	$abbr
	 */
	public function __construct($abbr = '') {
		// set self::$languages
		require_once(ROOT_DIR.'lang/languages.inc.php');
		$this->abbr = $this->determineLanguage($abbr);
		$this->name = self::$languages[$this->abbr]['name'];
		require_once(ROOT_DIR.'lang/global.lang.php');
		require_once(ROOT_DIR.'lang/'.self::$languages[$this->abbr]['file'].'.lang.php');
	}
	
	/**
	 * Returns the language variable in this language
	 * see alias lang($langVar) for use in templates.
	 * @param 	string 	$langVar
	 * @return 	string
	 */
	public function getLanguageItem($langVar) {
		// search in global vars first
		if (array_key_exists($langVar,self::$globalLangVars)) {
			return self::$globalLangVars[$langVar];
		}
		// then search in language specific vars
		if (array_key_exists($langVar,$this->langVars)) {
			return $this->langVars[$langVar];
		}
		// nothing found -> let's print the langVar instead
		return $langVar;
	}
	
	/**
	 * Returns an array containing the names of all known
	 * languages in their respective languages.
	 * @return 	string
	 */
	public function getLanguageNames() {
		$languageNames = array();
		foreach(self::$languages as $lang) {
			$languageNames[] = $lang['name'];
		}
		return $languageNames;
	}
	
	/**
	 * Determines the language to be used and returns its
	 * abbreviation. Checks the supplied abbreviation first,
	 * next the http request and if that still doesn't check out,
	 * defaults to english.
	 * @param 	string 	$abbr
	 * @return 	string
	 */
	protected function determineLanguage($abbr) {
		// does the requested language exist?
		if ($abbr && array_key_exists($abbr, self::$languages)) {
			return $abbr;
		} else if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))  {
			// try browser language settings
			$httpAcceptLanguages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
			foreach ($httpAcceptLanguages as $httpAcceptLanguage) {
				// strip stuff like 'de-at;q=0.5' to 'de'
				$cleanAbbr = preg_replace('@^([a-z]{2}).*@','$1',$httpAcceptLanguage);
				if (array_key_exists($cleanAbbr, self::$languages)) {
					return $cleanAbbr;
				}
			}
		} else {
			// default to english
			return 'en';
		}
	}
}
