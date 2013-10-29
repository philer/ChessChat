<?php

/**
 * A ajax controller can respond to an ajax request
 * @author Philipp Miller
 */
interface AjaxController {
	
	/**
	 * If the incoming Request is an AJAx request, an AjaxController's
	 * handleAjaxRequest method will be called for response.
	 */
	public function handleAjaxRequest();
	
}
