<?php

/**
 * Default implementation of RequestController interface for
 * convenient inheritance
 * @author  Philipp Miller
 */
abstract class AbstractRequestController implements RequestController {
	
	/**
	 * This page's title as to be used for example in the html title tag
	 * @var string
	 */
	protected $pageTitle = '';
	
	/**
	 * This request's route
	 * @var string
	 */
	protected $route = '';
	
	public function __construct() {
		$controllerName = get_class($this);
		$this->route = substr($controllerName, 0, strlen($controllerName) - 10)
		             . '/';
	}
	
	/**
	 * Returns this page's title as to be used for example in the html title tag
	 * @return string
	 */
	public function getPageTitle() {
		return $this->pageTitle;
	}
	
	/**
	 * Returns this Requests canonical route
	 * @return string
	 */
	public function getCanonicalRoute() {
		return $this->route;
	}
	
}
