<?php

/**
 * A request controller can respond to a regular
 * request and respond with an individual page.
 * @author Philipp Miller
 */
interface RequestController {
	
	/**
	 * The RequestController's handleRequest method takes care of
	 * responding to the indivudual request.
	 */
	public function handleRequest(array $route);
	
	/**
	 * Returns this page's title e.g. for use in the
	 * html title tag
	 * @return string
	 */
	public function getPageTitle();
	
	/**
	 * Returns this page's default route
	 * @return string
	 */
	public function getCanonicalRoute();
	
}
