<?php

class PermissionDeniedException extends RequestException {
	
	public function __construct($message = '') {
		$this->title = 'Permission Denied';
		$this->http_code = 403;
		header('HTTP/1.0 403 Forbidden');
		parent::__construct($message);
	}
	
}
