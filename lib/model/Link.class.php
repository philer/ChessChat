<?php

/**
 * Represents an item in a menu
 * mainly for use in templates
 */
class Link {
	
	/**
	 * Language variable to be used for
	 * the display name of this Link
	 * @var string
	 */
	protected $name = '';
	
	/**
	 * Route relative to HOST
	 * @var string
	 */
	protected $route = '';
	
	/**
	 * Invisible Links will not be displayed.
	 * @var boolean
	 */
	protected $visible = true;
	
	/**
	 * Creates a new Link.
	 * @param string  $name    we'll try to resolve as language variable
	 * @param string  $route
	 * @param boolean $visible
	 */
	public function __construct($name, $route = '', $visible = true) {
		$this->name    = $name;
		$this->route   = $route;
		$this->visible = $visible;
	}
	
	/**
	 * Returns the ready HTML anchor tag
	 * with resolved language variable and URL.
	 * Returns an empty string if $visible is false.
	 * @return string
	 */
	public function __toString() {
		if ($this->visible) {
			return '<a href="' . $this->url() . '">'
				 . Core::getLanguage()->getLanguageItem($this->name)
				 . '</a>';
		}
		else return '';
	}
	
	/**
	 * Returns the name of this Link
	 * (raw language variable)
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * URL of this Link ready for use in template
	 * @return string
	 */
	public function url() {
		return Util::url($this->route);
	}
	
	/**
	 * Invisible Links should not be displayed
	 * @return boolean
	 */
	public function isVisible() {
		return $this->visible;
	}
	
}
