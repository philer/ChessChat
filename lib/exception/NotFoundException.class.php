<?php

class NotFoundException extends RequestException {

	public function __construct($message = '') {
		$this->title = 'Not Found';
		$this->httpCode = 404;
		header('HTTP/1.0 404 Not Found');
		parent::__construct($message);
	}
	
}
