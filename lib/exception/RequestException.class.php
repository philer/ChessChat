<?php

class RequestException extends Exception {
	
	protected $title = '';
	protected $http_code = 200;
	
	/**
	 * Creates a new RequestException.
	 * Use this when overriding for setting the $message
	 * @param 	string 	$message
	 */
	public function __construct($message) {
		$this->message = $message;
		// http_response_code($this->http_code); // PHP 5.4
	}
	
	/**
	 * Display a nice message to user
	 */
	public function show() {
		Core::getTemplateEngine()->addVar('errorTitle', $this->getTitle());
		Core::getTemplateEngine()->addVar('errorMessage', $this->message);
		Core::getTemplateEngine()->showPage('error');
	}
	
	/**
	 * Prepends existing http response code to title
	 * @return 	string
	 */
	public function getTitle() {
		if ($this->http_code >= 400) {
			return $this->http_code . ' ' . $this->title;
		} else {
			return $this->title;
		}
	}
	
}
