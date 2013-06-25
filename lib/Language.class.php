<?php
/**
 * Represents a selectable Language.
 * The Language has to defined in lang/languages.inc.php.
 * @author Philipp Miller
 */
final class Language {
	
	/**
	 * Language code for this language
	 * as used by request headers etc.
	 * @var 	string
	 */
	protected $langCode;
	
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
	 * @param 	string 	$langCode
	 */
	public function __construct($langCode = '') {
		// set self::$languages
		require_once(ROOT_DIR.'lang/languages.inc.php');
		$this->langCode = $this->determineLanguage($langCode);
		$this->name = self::$languages[$this->langCode]['name'];
		require_once(ROOT_DIR.'lang/global.lang.php');
		require_once(ROOT_DIR.'lang/'.self::$languages[$this->langCode]['file'].'.lang.php');
	}
	
	/**
	 * Returns the language variable in this language
	 * see alias lang($langVar) for use in templates.
	 * @param 	string 	$langVar
	 * @return 	string
	 */
	public function getLanguageItem($langVar) {
		// search in language specific vars first
		if (array_key_exists($langVar,$this->langVars)) {
			return $this->langVars[$langVar];
		}
		// then search in global vars
		if (array_key_exists($langVar,self::$globalLangVars)) {
			return self::$globalLangVars[$langVar];
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
	 * language code. Checks the supplied language code first,
	 * next the http request and if that still doesn't check out,
	 * defaults to english.
	 * @param 	string 	$langCode
	 * @return 	string
	 */
	protected function determineLanguage($langCode) {
		// does the requested language exist?
		if (empty($langCode)) $langCode = Core::getUser()->getLanguage();
		
		if (!empty($langCode) && array_key_exists($langCode, self::$languages)) {
			return $langCode;
		} elseif (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))  {
			// try browser language settings
			$httpAcceptLanguages = explode(',',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
			foreach ($httpAcceptLanguages as $httpAcceptLanguage) {
				// strip stuff like 'de-at;q=0.5' to 'de'
				$cleanLangCode = preg_replace('@^([a-z]{2}).*@','$1',$httpAcceptLanguage);
				if (array_key_exists($cleanLangCode, self::$languages)) {
					return $cleanLangCode;
				}
			}
		} else {
			// default to english
			return 'en';
		}
	}
	
	/**
	 * Returns this language's code.
	 * @return 	string
	 */
	public function getLanguageCode() {
		return $this->langCode;
	}
	
	/**
	 * Returns this language's name.
	 * @return 	string
	 */
	public function __toString() {
		return $this->name;
	}
}
