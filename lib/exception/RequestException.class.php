<?php

class RequestException extends Exception {
	
	protected $title = '';
	protected $httpCode = 200;
	
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
	 * Display a nice message to user.
	 * Detailed messages in debug mode only.
	 */
	public function show() {
		if (DEBUG_MODE) {
			Core::getTemplateEngine()->addVar('errorTitle', $this->getTitle());
			Core::getTemplateEngine()->addVar('errorMessage', $this->message);
		} else {
			Core::getTemplateEngine()->addVar('errorMessage', 'exception.' . $this->httpCode . '.msg');
		}
		Core::getTemplateEngine()->showPage('_error');
	}
	
	/**
	 * Prepends existing http response code to title
	 * @return 	string
	 */
	public function getTitle() {
		if ($this->httpCode >= 400) {
			return $this->httpCode . ' ' . $this->title;
		} else {
			return $this->title;
		}
	}
	
}
